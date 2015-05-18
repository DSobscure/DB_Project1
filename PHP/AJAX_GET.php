<?php
    session_start();
    require_once("Database.php");
    require_once("Page.php");
    $action = isset($_GET["action"])?$_GET["action"]:"noAction";

    switch($action)
    {
        case "ToRegisterPage":
            {
                $identity = isset($_GET["Identity"])?$_GET["Identity"]:"";
                echo
                    ATHeader(). 
                    RegisterPage($identity,$Database);
                exit;
            }
            break;
        case "Logout":
            {
                unset($_SESSION["UserAccount"]);
                unset($_SESSION["Identity"]);
                unset($_SESSION["ID"]);
                echo IndexPage($Database);
                exit;
            }
            break;
        case "ToIndexPage":
            {
                echo IndexPage($Database);
                exit;
            }
            break;
        case "GetSeekerList":
            {
                echo JobSeekerTable($Database);
                exit;
            }
            break;
        case "AddNewJob":
            {
                echo NewJobLine($Database);
                exit;
            }
            break;
        case "EditJob":
            {
                $jobID = (isset($_GET["JobID"]))?$_GET["JobID"]:"";
                $editParameter["Occupation"] =  (isset($_GET["Occupation"]))?$_GET["Occupation"]:"";
                $editParameter["Location"] =  (isset($_GET["Location"]))?$_GET["Location"]:"";
                $editParameter["WorkTime"] =  (isset($_GET["WorkTime"]))?$_GET["WorkTime"]:"";
                $editParameter["Education"] =  (isset($_GET["Education"]))?$_GET["Education"]:"";
                $editParameter["WorkingExperience"] =  (isset($_GET["WorkingExperience"]))?$_GET["WorkingExperience"]:"";
                $editParameter["Salary"] =  (isset($_GET["Salary"]))?$_GET["Salary"]:"";
                echo EditJobLine($jobID,$Database,$editParameter);
                exit;
            }
            break;
        case "VacancyNextPage":
            {
                $PageNumber = (isset($_GET["PageNumber"]))?$_GET["PageNumber"]:0;
                echo VacancyTable($Database,$PageNumber+1);
                exit;
            }
            break;
        case "VacancyPrePage":
            {
                $PageNumber = (isset($_GET["PageNumber"]))?$_GET["PageNumber"]:0;
                echo VacancyTable($Database,$PageNumber-1);
                exit;
            }
            break;
        case "SeekerNextPage":
            {
                $PageNumber = (isset($_GET["PageNumber"]))?$_GET["PageNumber"]:0;
                echo JobSeekerTable($Database,$PageNumber+1);
                exit;
            }
            break;
        case "SeekerPrePage":
            {
                $PageNumber = (isset($_GET["PageNumber"]))?$_GET["PageNumber"]:0;
                echo JobSeekerTable($Database,$PageNumber-1);
                exit;
            }
            break;
        case "VacancyJumpPage":
            {
                $PageNumber = (isset($_GET["PageNumber"]))?$_GET["PageNumber"]:0;
                echo VacancyTable($Database,$PageNumber);
                exit;
            }
            break;
        case "VacancySortByID":
            {
                unset($_SESSION["VacancyOrder"]);
                echo VacancyTable($Database);
                exit;
            }
            break;
        case "VacancySortBySalaryASC":
            {
                $_SESSION["VacancyOrder"]="ASC";
                echo VacancyTable($Database);
                exit;
            }
            break;
        case "VacancySortBySalaryDESC":
            {
                $_SESSION["VacancyOrder"]="DESC";
                echo VacancyTable($Database);
                exit;
            }
            break;
        case "VacancySearch":
            {
                unset($_SESSION["SearchCondition_Occupation"]);
                unset($_SESSION["SearchCondition_Location"]);
                unset($_SESSION["SearchCondition_WorkTime"]);
                unset($_SESSION["SearchCondition_Education"]);
                unset($_SESSION["SearchCondition_Experience"]);
                unset($_SESSION["SearchCondition_Salary"]);
                $searchCondition="";
                #null check
                {
                    $occupation=(isset($_GET["Occupation"]))?$_GET["Occupation"]:"";
                    $location=(isset($_GET["Location"]))?$_GET["Location"]:"";
                    $workTime=(isset($_GET["WorkTime"]))?$_GET["WorkTime"]:"";
                    $education=(isset($_GET["Education"]))?$_GET["Education"]:"";
                    $workingExperience=(isset($_GET["WorkingExperience"]))?$_GET["WorkingExperience"]:"";
                    $salaryRangeSign=(isset($_GET["SalaryRangeSign"]))?$_GET["SalaryRangeSign"]:"";
                    $salaryRangeNumber=(isset($_GET["SalaryRangeNumber"]))?$_GET["SalaryRangeNumber"]:"";
                }
                #occupation check
                {
                    $result = $Database->prepare("SELECT id,count(*)as count FROM occupation where occupation=:occupation");
                    $result->bindParam(":occupation",$occupation);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==1)
                        {
                            $_SESSION["SearchCondition_Occupation"]=$occupation;
                            $searchCondition.=" and recruit.occupation_id=".$row["id"]." ";
                        }
                    }
                }
                #location check
                {
                    $result = $Database->prepare("SELECT id,COUNT(*) as count FROM location where location=:location");
                    $result->bindParam(":location",$location);
                    if($result->execute())
                    {
                        $row=$result->fetch();
                        if($row["count"]==1)
                        {
                            $_SESSION["SearchCondition_Location"]=$location;
                            $searchCondition.=" and recruit.location_id=".$row["id"]." ";
                        }
                    }
                }
                #workTime check
                {
                    if(!WorkTimeCheckError($workTime))
                    {
                        $_SESSION["SearchCondition_WorkTime"]=$workTime;
                        $searchCondition.=" and recruit.working_time='".$workTime."' ";
                    }
                }
                #education check
                {
                    if(!EducationCheckError($education))
                    {
                        $_SESSION["SearchCondition_Education"]=$education;
                        $searchCondition.=" and recruit.education='".$education."' ";
                    }
                }
                #working experience check
                {
                    if(!WorkingExperienceCheckError($workingExperience))
                    {
                        $_SESSION["SearchCondition_Experience"]=$workingExperience;
                        $searchCondition.=" and recruit.experience=".WorkingExperienceTranslate($workingExperience)." ";
                    }
                }
                #salary range check
                {
                    if($salaryRangeSign=="<"||$salaryRangeSign==">")
                    {
                        if(is_numeric($salaryRangeNumber)&&$salaryRangeNumber>=0)
                        {
                            $_SESSION["SearchCondition_Salary"]=$salaryRangeSign." ".$salaryRangeNumber;
                            $searchCondition.=" and recruit.salary $salaryRangeSign $salaryRangeNumber ";
                        }
                    }
                }
                $_SESSION["SearchCondition"] = $searchCondition;
                echo VacancyTable($Database,1);
                exit;
            }
            break;
        case "ClearSearchCondition":
            {
                unset($_SESSION["SearchCondition_Occupation"]);
                unset($_SESSION["SearchCondition_Location"]);
                unset($_SESSION["SearchCondition_WorkTime"]);
                unset($_SESSION["SearchCondition_Education"]);
                unset($_SESSION["SearchCondition_Experience"]);
                unset($_SESSION["SearchCondition_Salary"]);
                unset($_SESSION["SearchCondition"]);
                echo VacancyTable($Database);
                exit;
            }
            break;
        case "GetFavoriteList":
            {
                echo FavoriteList($Database);
                exit;
            }
            break;
        case "GetMyJobTable":
            {
                echo MyJobTable($Database);
                exit;
            }
            break;
    }
?>
