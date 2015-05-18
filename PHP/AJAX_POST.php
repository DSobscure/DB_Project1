<?php
    session_start();
    require_once("Database.php");
    require_once("Page.php");
    $action = isset($_POST["action"])?$_POST["action"]:"noAction";

    switch($action)
    {
        case "Login":
            {
                switch($_POST["Identity"])
                {
                    case "employer":
                        $result = $Database->prepare("SELECT id,COUNT(*) as count FROM employer where account=:account and password=:password");
                        break;
                    case "seeker":
                        $result = $Database->prepare("SELECT id,COUNT(*) as count FROM user where account=:account and password=:password");
                        break;
                }
                $result->bindParam(":account",$_POST["Account"]);
                $result->bindParam(":password",hash("sha512",$_POST["Password"]));
                if($result->execute())
                {
                    $row=$result->fetch();
                    if($row["count"]==1)
                    {
                        $_SESSION["UserAccount"]=$_POST["Account"];
                        $_SESSION["Identity"]=$_POST["Identity"];
                        $_SESSION["ID"]=$row["id"];
                        echo IndexPage($Database);
                        exit;
                    }
                    else
                    {
                        echo
                            ATHeader().
                            "<div class='alert'>帳號或密碼錯誤</div>". 
                            ATContent($Database);
                        exit;
                    }
                }
                else
                {
                    echo
                        ATHeader(). 
                        "<div class='alert'>資料庫錯誤</div>".
                        ATContent($Database);
                    exit;
                }           
            }
            break;
        case "EmployerRegister":
            {
                echo ATHeader();
                #null check
                {
                    if(!isset($_POST["Account"])||$_POST["Account"]=="")
                    {
                        echo 
                            "<div class='alert'>帳號不可為空</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    if(!isset($_POST["Password"])||$_POST["Password"]=="")
                    {
                        echo 
                            "<div class='alert'>密碼不可為空</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    if(!isset($_POST["PasswordCheck"])||$_POST["PasswordCheck"]=="")
                    {
                        echo 
                            "<div class='alert'>確認密碼不可為空</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    if(!isset($_POST["PhoneNumber"])||$_POST["PhoneNumber"]=="")
                    {
                        echo 
                            "<div class='alert'>電話不可為空</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    if(!isset($_POST["Email"])||$_POST["Email"]=="")
                    {
                        echo 
                            "<div class='alert'>電子信箱不可為空</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                }
                #account check
                {
                    if(strlen($_POST["Account"])<6)
                    {
                        echo 
                            "<div class='alert'>帳號過短</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    if(strlen($_POST["Account"]) > 20)
                    {
                        echo 
                            "<div class='alert'>帳號過長</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    $result = $Database->prepare("SELECT COUNT(*) as count FROM employer where account=:account");
                    $result->bindParam(":account",$_POST["Account"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]!=0)
                        {
                        echo 
                            "<div class='alert'>帳號已被使用</div>".
                            EmployerRegisterPage();
                        exit;
                        }
                        $account=str_replace("<","＜",$_POST["Account"]);
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                }
                #password check
                {
                    if(strlen($_POST["Password"])<6)
                    {
                        echo 
                            "<div class='alert'>密碼過短</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    if($_POST["Password"]!=$_POST["PasswordCheck"])
                    {
                        echo 
                            "<div class='alert'>確認密碼與密碼不相符</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    $password=hash("sha512",$_POST["Password"]);
                }
                #phone check
                {
                    if(!preg_match("/^[0-9]{8,10}$/",$_POST["PhoneNumber"]))
                    {
                        echo 
                            "<div class='alert'>電話格式錯誤</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    $phone=$_POST["PhoneNumber"];
                }
                #mail check
                {
                    if(!preg_match("/^[\S]+@[\S]+\.[\S]{2,3}$/",$_POST["Email"]))
                    {
                        echo 
                            "<div class='alert'>信箱格式錯誤</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                    $mail=str_replace("<","＜",$_POST["Email"]);
                }
                #register
                {
                    $result = $Database->prepare("INSERT INTO employer (account,password,phone,mail) values (:account,:password,:phone,:mail)");
                    $result->bindParam(":account",$account);
                    $result->bindParam(":password",$password);
                    $result->bindParam(":phone",$phone);
                    $result->bindParam(":mail",$mail);
                    if($result->execute())
                    {
                        $result = $Database->prepare("SELECT id FROM employer where account=:account");
                        $result->bindParam(":account",$account);
                        if($result->execute())
                        {
                            $row=$result->fetch();
                            $employer_id=$row["id"];
                        }
                        else
                        {
                            echo 
                                "<div class='alert'>註冊成功，請重新登入</div>".
                                ATContent($Database);
                            exit;
                        }
                        $_SESSION["UserAccount"] = $account;
                        $_SESSION["Identity"] = "employer";
                        $_SESSION["ID"] = $employer_id;
                        echo
                            "<div class='alert'>註冊成功</div>".
                            ATContent($Database);                       
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>註冊失敗，請再試一次!</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                }
            }
            break;
        case "SeekerRegister";
            {
                echo ATHeader();
                #null check
                {
                    if(!isset($_POST["Account"])||$_POST["Account"]=="")
                    {
                        echo 
                            "<div class='alert'>帳號不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(!isset($_POST["Password"])||$_POST["Password"]=="")
                    {
                        echo 
                            "<div class='alert'>密碼不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(!isset($_POST["PasswordCheck"])||$_POST["PasswordCheck"]=="")
                    {
                        echo 
                            "<div class='alert'>確認密碼不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(!isset($_POST["PhoneNumber"])||$_POST["PhoneNumber"]=="")
                    {
                        echo 
                            "<div class='alert'>電話不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(!isset($_POST["Gender"])||$_POST["Gender"]=="")
                    {
                        echo 
                            "<div class='alert'>性別不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(!isset($_POST["Age"])||$_POST["Age"]=="")
                    {
                        echo 
                            "<div class='alert'>年齡不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(!isset($_POST["Email"])||$_POST["Email"]=="")
                    {
                        echo 
                            "<div class='alert'>電子信箱不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(!isset($_POST["Salary"])||$_POST["Salary"]=="")
                    {
                        echo 
                            "<div class='alert'>預期新水不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(!isset($_POST["Education"])||$_POST["Education"]=="")
                    {
                        echo 
                            "<div class='alert'>教育程度不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(!isset($_POST["Email"])||$_POST["Email"]=="")
                    {
                        echo 
                            "<div class='alert'>電子信箱不可為空</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                }
                #account check
                {
                    if(strlen($_POST["Account"])<6)
                    {
                        echo 
                            "<div class='alert'>帳號過短</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if(strlen($_POST["Account"]) > 20)
                    {
                        echo 
                            "<div class='alert'>帳號過長</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    $result = $Database->prepare("SELECT COUNT(*) as count FROM user where account=:account");
                    $result->bindParam(":account",$_POST["Account"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]!=0)
                        {
                            echo 
                                "<div class='alert'>帳號已被使用</div>".
                                SeekerRegisterPage($Database);
                            exit;
                        }
                        $account=str_replace("<","＜",$_POST["Account"]);
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                }
                #password check
                {
                    if(strlen($_POST["Password"])<6)
                    {
                        echo 
                            "<div class='alert'>密碼過短</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    if($_POST["Password"]!=$_POST["PasswordCheck"])
                    {
                        echo 
                            "<div class='alert'>確認密碼與密碼不相符</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    $password=hash("sha512",$_POST["Password"]);
                }
                #phone check
                {
                    if(!preg_match("/^[0-9]{8,10}/",$_POST["PhoneNumber"]))
                    {
                        echo 
                            "<div class='alert'>電話格式錯誤</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    $phone=$_POST["PhoneNumber"];
                }
                #gender check
                {
                    if($_POST["Gender"]!="male"&&$_POST["Gender"]!="female")
                    {
                        echo 
                            "<div class='alert'>性別格式錯誤</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    $gender=$_POST["Gender"];
                }
                #age check
                {
                    if($_POST["Age"]<15||$_POST["Age"]>75)
                    {
                        echo 
                            "<div class='alert'>年齡不符合規定</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    $age=$_POST["Age"];
                }
                #mail check
                {
                    if(!preg_match("/^[\S]+@[\S]+\.[\S]{2,3}$/",$_POST["Email"]))
                    {
                        echo 
                            "<div class='alert'>信箱格式錯誤</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    $mail=str_replace("<","＜",$_POST["Email"]);
                }
                #salary check
                {
                    if($_POST["Salary"]<=0)
                    {
                        echo 
                            "<div class='alert'>薪資必須為正數</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    $salary=$_POST["Salary"];
                }
                #education check
                {
                    if(EducationCheckError($_POST["Education"]))
                    {
                        echo 
                            "<div class='alert'>學歷錯誤</div>".
                            SeekerRegisterPage($Database);
                        exit;
                    }
                    $education=$_POST["Education"];
                }
                #specialty check
                {
                    if(isset($_POST["Specialty"]))
                    {
                        $queryResult=$Database->query("SELECT id,specialty FROM specialty");
                        while($row=$queryResult->fetch())
                        {
                            $standardSpecialties[$row["specialty"]] = $row["id"];
                        }
                        foreach($_POST["Specialty"] as $specialty)
                        {
                            if(!array_key_exists($specialty,$standardSpecialties))
                            {
                                echo
                                    "<div class='alert'>專長錯誤</div>".
                                    SeekerRegisterPage($Database);
                                exit;
                            }
                            $specialtiesID[] = $standardSpecialties[$specialty];
                        }
                    }                    
                }
                #register
                {
                    $sql = "INSERT INTO user (account,password,education,expected_salary,phone,gender,age,email) values (:account,:password,:education,:expected_salary,:phone,:gender,:age,:email)";
                    $result = $Database->prepare($sql);

                    $result->bindParam(":account",$account);
                    $result->bindParam(":password",$password);
                    $result->bindParam(":education",$education);
                    $result->bindParam(":expected_salary",$salary);
                    $result->bindParam(":phone",$phone);
                    $result->bindParam(":gender",$gender);
                    $result->bindParam(":age",$age);
                    $result->bindParam(":email",$mail);

                    if($result->execute())
                    {
                        $result = $Database->prepare("SELECT id FROM user where account=:account");
                        $result->bindParam(":account",$account);
                        if($result->execute())
                        {
                            $row=$result->fetch();
                            $seeker_id=$row["id"];
                            if(isset($specialtiesID))
                            {
                                $sql = "INSERT INTO user_specialty (user_id,specialty_id) value (:user_id0,:specialty_id0)";
                                $specialtyCount = count($specialtiesID);
                                for($i=1;$i<$specialtyCount;$i++)
                                {
                                    $sql .= ",(:user_id$i,:specialty_id$i)";
                                }
                                $result = $Database->prepare($sql);
                                for($i=0;$i<$specialtyCount;$i++)
                                {
                                    $result->bindParam(":user_id$i",$seeker_id);
                                    $result->bindParam(":specialty_id$i",$specialtiesID[$i]);
                                }
                                $result->execute();
                            }
                        }
                        else
                        {
                            echo 
                                "<div class='alert'>註冊成功，請重新登入</div>".
                                ATContent($Database);
                            exit;
                        }
                        $_SESSION["UserAccount"] = $account;
                        $_SESSION["Identity"] = "seeker";
                        $_SESSION["ID"] = $seeker_id;
                        echo
                            "<div class='alert'>註冊成功</div>".
                            ATContent($Database);
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>註冊失敗，請再試一次!</div>".
                            EmployerRegisterPage();
                        exit;
                    }
                }
            }
            break;
        case "SaveNewJob":
            {
                #identity check
                {
                    if($_SESSION["Identity"]!="employer")
                    {
                        echo 
                            "<div class='alert'>身分錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #null check
                {
                    if(!isset($_POST["Occupation"])||$_POST["Occupation"]=="")
                    {
                        echo 
                            "<div class='alert'>工作不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["Location"])||$_POST["Location"]=="")
                    {
                        echo 
                            "<div class='alert'>地點不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["WorkTime"])||$_POST["WorkTime"]=="")
                    {
                        echo 
                            "<div class='alert'>工作時間不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["Education"])||$_POST["Education"]=="")
                    {
                        echo 
                            "<div class='alert'>教育程度不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["WorkingExperience"])||$_POST["WorkingExperience"]=="")
                    {
                        echo 
                            "<div class='alert'>工作經驗不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["Salary"])||$_POST["Salary"]=="")
                    {
                        echo 
                            "<div class='alert'>薪水不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #occupation check
                {
                    $result = $Database->prepare("SELECT id,count(*)as count FROM occupation where occupation=:occupation");
                    $result->bindParam(":occupation",$_POST["Occupation"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此工作</div>".
                                VacancyTable($Database);
                            exit;
                        }
                        $occupation_id=$row["id"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #location check
                {
                    $result = $Database->prepare("SELECT id,COUNT(*) as count FROM location where location=:location");
                    $result->bindParam(":location",$_POST["Location"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此地點</div>".
                                VacancyTable($Database);
                            exit;
                        }
                        $location_id=$row["id"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #work time check
                {
                    if(WorkTimeCheckError($_POST["WorkTime"]))
                    {
                        echo 
                            "<div class='alert'>工作時間錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $working_time=$_POST["WorkTime"];
                }
                #education check
                {
                    if(EducationCheckError($_POST["Education"]))
                    {
                        echo 
                            "<div class='alert'>學歷錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $education=$_POST["Education"];
                }
                #working experience check
                {
                    if(WorkingExperienceCheckError($_POST["WorkingExperience"]))
                    {
                        echo 
                            "<div class='alert'>工作經驗錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $experience=WorkingExperienceTranslate($_POST["WorkingExperience"]);
                }
                #salary check
                {
                    if($_POST["Salary"]<0)
                    {
                        echo 
                            "<div class='alert'>薪資必須為正數</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $salary=$_POST["Salary"];
                }
                #add new
                {
                    $result = $Database->prepare("INSERT INTO recruit (employer_id,occupation_id,location_id,working_time,education,experience,salary) 
                                                  values (:employer_id,:occupation_id,:location_id,:working_time,:education,:experience,:salary)");
                    $result->bindParam(":employer_id",$_SESSION["ID"]);
                    $result->bindParam(":occupation_id",$occupation_id);
                    $result->bindParam(":location_id",$location_id);
                    $result->bindParam(":working_time",$working_time);
                    $result->bindParam(":education",$education);
                    $result->bindParam(":experience",$experience);
                    $result->bindParam(":salary",$salary);
                    if($result->execute())
                    {
                        echo 
                            "<div class='alert'>新增成功!</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>新增失敗，請再試一次!</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
            }
            break;
        case "DeleteJob":
            {
                #null check
                {
                    if(!isset($_POST["JobID"])||$_POST["JobID"]=="")
                    {
                        echo 
                            "<div class='alert'>刪除項目不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #identity check
                {
                    $result = $Database->prepare("SELECT employer_id,count(*)as count FROM recruit where id=:id");
                    $result->bindParam(":id",$_POST["JobID"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此工作</div>".
                                VacancyTable($Database);
                            exit;
                        }
                        $jobID = $_POST["JobID"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if($_SESSION["Identity"]!="employer"||$_SESSION["ID"]!=$row["employer_id"])
                    {
                        echo 
                            "<div class='alert'>身分錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #delete
                {
                    $result = $Database->prepare("DELETE from recruit where id=:id");
                    $result->bindParam(":id",$jobID);
                    if($result->execute())
                    {
                        echo
                            VacancyTable($Database);
                        exit;
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
            }
            break;
        case "UpdateJob":
            {
                #null check
                {
                    if(!isset($_POST["JobID"])||$_POST["JobID"]=="")
                    {
                        echo 
                            "<div class='alert'>修改項目不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["Occupation"])||$_POST["Occupation"]=="")
                    {
                        echo 
                            "<div class='alert'>工作不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["Location"])||$_POST["Location"]=="")
                    {
                        echo 
                            "<div class='alert'>地點不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["WorkTime"])||$_POST["WorkTime"]=="")
                    {
                        echo 
                            "<div class='alert'>工作時間不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["Education"])||$_POST["Education"]=="")
                    {
                        echo 
                            "<div class='alert'>教育程度不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["WorkingExperience"])||$_POST["WorkingExperience"]=="")
                    {
                        echo 
                            "<div class='alert'>工作經驗不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if(!isset($_POST["Salary"])||$_POST["Salary"]=="")
                    {
                        echo 
                            "<div class='alert'>薪水不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #identity check
                {
                    $result = $Database->prepare("SELECT employer_id,count(*)as count FROM recruit where id=:id");
                    $result->bindParam(":id",$_POST["JobID"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此工作</div>".
                                VacancyTable($Database);
                            exit;
                        }
                        $jobID = $_POST["JobID"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    if($_SESSION["Identity"]!="employer"||$_SESSION["ID"]!=$row["employer_id"])
                    {
                        echo 
                            "<div class='alert'>身分錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #occupation check
                {
                    $result = $Database->prepare("SELECT id,count(*)as count FROM occupation where occupation=:occupation");
                    $result->bindParam(":occupation",$_POST["Occupation"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此工作</div>".
                                VacancyTable($Database);
                            exit;
                        }
                        $occupation_id=$row["id"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #location check
                {
                    $result = $Database->prepare("SELECT id,COUNT(*) as count FROM location where location=:location");
                    $result->bindParam(":location",$_POST["Location"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此地點</div>".
                                VacancyTable($Database);
                            exit;
                        }
                        $location_id=$row["id"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #work time check
                {
                    if(WorkTimeCheckError($_POST["WorkTime"]))
                    {
                        echo 
                            "<div class='alert'>工作時間錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $working_time=$_POST["WorkTime"];
                }
                #education check
                {
                    if(EducationCheckError($_POST["Education"]))
                    {
                        echo 
                            "<div class='alert'>學歷錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $education=$_POST["Education"];
                }
                #working experience check
                {
                    if(WorkingExperienceCheckError($_POST["WorkingExperience"]))
                    {
                        echo 
                            "<div class='alert'>工作經驗錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $experience=WorkingExperienceTranslate($_POST["WorkingExperience"]);
                }
                #salary check
                {
                    if($_POST["Salary"]<0)
                    {
                        echo 
                            "<div class='alert'>薪資必須為正數</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $salary=$_POST["Salary"];
                }
                #add new
                {
                    $result = $Database->prepare("UPDATE recruit SET occupation_id=:occupation_id, location_id=:location_id,
                                                  working_time=:working_time, education=:education, experience=:experience,
                                                  salary=:salary where id=:id");
                    $result->bindParam(":id",$jobID);
                    $result->bindParam(":occupation_id",$occupation_id);
                    $result->bindParam(":location_id",$location_id);
                    $result->bindParam(":working_time",$working_time);
                    $result->bindParam(":education",$education);
                    $result->bindParam(":experience",$experience);
                    $result->bindParam(":salary",$salary);
                    if($result->execute())
                    {
                        echo 
                            "<div class='alert'>更新成功!</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>更新失敗，請再試一次!</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
            }
            break;
        case "AddFavorite":
            {
                #null check
                {
                    if(!isset($_POST["JobID"])||$_POST["JobID"]=="")
                    {
                        echo 
                            "<div class='alert'>加入項目不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #identity check
                {
                    if($_SESSION["Identity"]!="seeker")
                    {
                        echo 
                            "<div class='alert'>身分錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $result = $Database->prepare("SELECT count(*)as count FROM recruit where id=:id");
                    $result->bindParam(":id",$_POST["JobID"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此工作</div>".
                                VacancyTable($Database);
                            exit;
                        }
                        $jobID = $_POST["JobID"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #add to favorite list
                {
                    $result = $Database->prepare("INSERT INTO favorite (user_id,recruit_id) values (:seekerID,:jobID)");
                    $result->bindParam(":seekerID",$_SESSION["ID"]);
                    $result->bindParam(":jobID",$jobID);
                    if($result->execute())
                    {
                        echo
                            VacancyTable($Database);
                        exit;
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
            }
            break;
        case "DeleteFavorite":
            {
                #null check
                {
                    if(!isset($_POST["FavoriteID"])||$_POST["FavoriteID"]=="")
                    {
                        echo 
                            "<div class='alert'>刪除項目不可為空</div>".
                            FavoriteList($Database);
                        exit;
                    }
                }
                #identity check
                {
                    $result = $Database->prepare("SELECT user_id,count(*)as count FROM favorite where recruit_id=:recruitID");
                    $result->bindParam(":recruitID",$_POST["FavoriteID"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此工作</div>".
                                FavoriteList($Database);
                            exit;
                        }
                        $favoriteID = $_POST["FavoriteID"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            FavoriteList($Database);
                        exit;
                    }
                    if($_SESSION["Identity"]!="seeker"||$_SESSION["ID"]!=$row["user_id"])
                    {
                        echo 
                            "<div class='alert'>身分錯誤</div>".
                            FavoriteList($Database);
                        exit;
                    }
                }
                #delete
                {
                    $result = $Database->prepare("DELETE from favorite where recruit_id=:favoriteID");
                    $result->bindParam(":favoriteID",$favoriteID);
                    if($result->execute())
                    {
                        echo
                            FavoriteList($Database);
                        exit;
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            FavoriteList($Database);
                        exit;
                    }
                }
            }
            break;
        case "ApplyJob":
            {
                #null check
                {
                    if(!isset($_POST["JobID"])||$_POST["JobID"]=="")
                    {
                        echo 
                            "<div class='alert'>申請項目不可為空</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #identity check
                {
                    if($_SESSION["Identity"]!="seeker")
                    {
                        echo 
                            "<div class='alert'>身分錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                    $result = $Database->prepare("SELECT count(*)as count FROM recruit where id=:id");
                    $result->bindParam(":id",$_POST["JobID"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此工作</div>".
                                VacancyTable($Database);
                            exit;
                        }
                        $jobID = $_POST["JobID"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
                #add to favorite list
                {
                    $result = $Database->prepare("INSERT INTO application (user_id,recruit_id) values (:seekerID,:jobID)");
                    $result->bindParam(":seekerID",$_SESSION["ID"]);
                    $result->bindParam(":jobID",$jobID);
                    if($result->execute())
                    {
                        echo
                            VacancyTable($Database);
                        exit;
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            VacancyTable($Database);
                        exit;
                    }
                }
            }
            break;
        case "Hire":
            {
                #null check
                {
                    if(!isset($_POST["JobID"])||$_POST["JobID"]=="")
                    {
                        echo 
                            "<div class='alert'>項目不可為空</div>".
                            MyJobTable($Database);
                        exit;
                    }
                }
                #identity check
                {
                    $result = $Database->prepare("SELECT employer_id,count(*)as count FROM recruit where id=:id");
                    $result->bindParam(":id",$_POST["JobID"]);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==0)
                        {
                            echo 
                                "<div class='alert'>無此工作</div>".
                                MyJobTable($Database);
                            exit;
                        }
                        $jobID = $_POST["JobID"];
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            MyJobTable($Database);
                        exit;
                    }
                    if($_SESSION["Identity"]!="employer"||$_SESSION["ID"]!=$row["employer_id"])
                    {
                        echo 
                            "<div class='alert'>身分錯誤</div>".
                            MyJobTable($Database);
                        exit;
                    }
                }
                #delete
                {
                    $result = $Database->prepare("DELETE from recruit where id=:id");
                    $result->bindParam(":id",$jobID);
                    if($result->execute())
                    {
                        echo
                            MyJobTable($Database);
                        exit;
                    }
                    else
                    {
                        echo 
                            "<div class='alert'>資料庫錯誤</div>".
                            MyJobTable($Database);
                        exit;
                    }
                }
            }
            break;
    }
?>