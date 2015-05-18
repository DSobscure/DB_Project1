<?php
    function VacancyTable($Database,$PageNumber=1)
    {
        $searchCondition = (isset($_SESSION["SearchCondition"]))?$_SESSION["SearchCondition"]:"";
        $workingExperience[""]="";
        $workingExperience[0]="0 Year";
        $workingExperience[1]="1 Year";
        $workingExperience[2]="2 Years";
        $workingExperience[3]="3 Years";
        $workingExperience[4]="4 Years";
        $workingExperience[5]="5+ Years";

        $queryResult=$Database->query("select count(*)as count from recruit where 1 $searchCondition");
        $row=$queryResult->fetch();
        $MaxPage=ceil($row["count"]/10);
        if($PageNumber<1)
            $PageNumber=1;
        if($PageNumber>$MaxPage)
            $PageNumber=$MaxPage;
        $startIndex=(($PageNumber>=1)?$PageNumber-1:0)*10;
        $sortDescription = (isset($_SESSION["VacancyOrder"]))?
                               (
                                   ($_SESSION["VacancyOrder"]=="ASC")?
                                       "order by recruit.salary asc,recruit.id asc"
                                       :
                                       "order by recruit.salary desc,recruit.id asc"
                               )
                               :
                               "order by recruit.id asc";
        $queryResult=$Database->query("select recruit.employer_id,recruit.id,occupation.occupation,location.location,
                                       recruit.working_time,recruit.education,recruit.experience,recruit.salary 
                                       FROM recruit,occupation,location
                                       where recruit.occupation_id=occupation.id and recruit.location_id=location.id
                                       ".$searchCondition." 
                                       ".$sortDescription."
                                       limit $startIndex,10");
        if(isset($_SESSION["Identity"])&&$_SESSION["Identity"]=="seeker"&&isset($_SESSION["ID"]))
        {
            $result=$Database->prepare("SELECT recruit.id FROM application join recruit
                                        WHERE application.user_id=:seekerID and application.recruit_id=recruit.id");
            $result->bindParam(":seekerID",$_SESSION["ID"]);
            $result->execute();
            while($row=$result->fetch())
            {
                $appliedJobs[]=$row["id"];
            }

            $result=$Database->prepare("SELECT recruit.id FROM favorite join recruit
                                        WHERE favorite.user_id=:seekerID and favorite.recruit_id=recruit.id");
            $result->bindParam(":seekerID",$_SESSION["ID"]);
            $result->execute();
            while($row=$result->fetch())
            {
                $favoriteList[]=$row["id"];
            }
        }
        $tableContent="";
        for($i=0;$i<10;$i++)
        {
            if($row=$queryResult->fetch())
            {
                $tableContent .= 
                    "
                        <tr id='Row$i' class='JobTableRow".($i%2)."'>
                            <td id='Job$i' onclick='VacancySortByID()'>".$row["id"]."</td>
                            <td id='Occupation$i'>".$row["occupation"]."</td>
                            <td id='Location$i'>".$row["location"]."</td>
                            <td id='WorkTime$i'>".$row["working_time"]."</td>
                            <td id='Education$i'>".$row["education"]."</td>
                            <td id='Experience$i'>".$workingExperience[$row["experience"]]."</td>
                            <td id='Salary$i'>".$row["salary"]."</td>
                            ";
                            if(isset($_SESSION["Identity"])&&isset($_SESSION["ID"])&&$_SESSION["Identity"]=="employer"&&$_SESSION["ID"]==$row["employer_id"])
                            {
                                $tableContent .=
                                    "<td>
                                        <input type='button' class='EditButton' value='Edit' onclick='EditJob(\"Row$i\",\"Job$i\",this)'>
                                        <input type='button' class='DeleteButton' value='Delete' onclick='DeleteJob(\"Job$i\",this)'>
                                     </td>
                                    ";
                            }
                            if(isset($_SESSION["Identity"])&&$_SESSION["Identity"]=="seeker")
                            {
                                if(isset($appliedJobs)&&in_array($row["id"],$appliedJobs))
                                {
                                    $applyButton="<label>Waiting for employer</label>";
                                }
                                else
                                {
                                    $applyButton="<input type='button' class='EditButton' value='Apply' onclick='ApplyJob(\"Job$i\",this)'>";
                                }

                                if(isset($favoriteList)&&in_array($row["id"],$favoriteList))
                                {
                                    $favoriteButton="<label>Already in favorite list</label>";
                                }
                                else
                                {
                                    $favoriteButton="<input type='button' class='DeleteButton' value='Favorite' onclick='AddFavorite(\"Job$i\",this)'>";
                                }
                                $tableContent .=
                                    "<td>
                                        $applyButton
                                        $favoriteButton
                                     </td>
                                    ";
                            }
                $tableContent .=
                        "</tr>    
                    ";
            }
        }
        if(isset($_SESSION["Identity"]))
        {
            switch($_SESSION["Identity"])
            {
                case "employer":
                    {
                        $table = 
                                "
                                    <div class='VacancyTableFull' name='VacancyTable' id='VacancyTable'>
                                        <div class='TableTitle'>
                                            <input type='button' class='PrePageButton' value='<' onclick='VacancyPrePage()'>
                                            Job Vacancy
                                            <input type='button' class='NextPageButton' value='>' onclick='VacancyNextPage()'>
                                        </div>
                                        <div id='JobTable'>
                                            <table class='JobTable'>
                                                ".VacancySearchBar($Database)."
                                                <tr class='JobTableSchema'>
                                                    <th onclick='VacancySortByID()'>ID</th>
                                                    <th>Occupation</th>
                                                    <th>Location</th>
                                                    <th>Work Time</th>
                                                    <th>Education Require</th>
                                                    <th>Minimum of Working Experience</th>
                                                    <th>Salary Per Month
                                                        <label class='SortDirectionUp' onclick='VacancySortBySalaryASC()'></label>
                                                        <label class='SortDirectionDown' onclick='VacancySortBySalaryDESC()'></label>
                                                    </th>
                                                    <th>Operation</th>
                                                </tr>
                                                ".$tableContent."
                                                <tr id='newJobRow' class='JobTableRowNewJob'>
                                                </tr>
                                            </table>
                                            <input type='button' class='AddNewJobButton' value='Add a New Job' onclick='AddNewJob(this)'>
                                            <input type='button' class='SeekerListButton' value='Job Seeker List' onclick='GetSeekerList()'>
                                            <input type='button' class='MyJobButton' value='Who Applies Your Job' onclick='GetMyJobTable()'>
                                            <a>第".$PageNumber."頁/共".$MaxPage."頁</a>
                                            <a>到第</a>
                                            <input type='number' id='JumpPageNumber'>
                                            <a>頁</a>
                                            <input type='button' id='JumpPageButton' value='跳轉' onclick='VacancyJumpPage()'>
                                            <input type='hidden' id='PageNumber' value=$PageNumber>
                                        </div>
                                    </div>
                                ";
                    }
                    break;
                case "seeker":
                    {
                        $table = 
                                "
                                    <div class='VacancyTableFull' name='VacancyTable' id='VacancyTable'>
                                        <div class='TableTitle'>
                                            <input type='button' class='PrePageButton' value='<' onclick='VacancyPrePage()'>
                                            Job Vacancy
                                            <input type='button' class='NextPageButton' value='>' onclick='VacancyNextPage()'>
                                        </div>
                                        <div id='JobTable'>
                                            <table class='JobTable'>
                                                ".VacancySearchBar($Database)."
                                                <tr class='JobTableSchema'>
                                                    <th onclick='VacancySortByID()'>ID</th>
                                                    <th>Occupation</th>
                                                    <th>Location</th>
                                                    <th>Work Time</th>
                                                    <th>Education Require</th>
                                                    <th>Minimum of Working Experience</th>
                                                    <th>Salary Per Month
                                                        <label class='SortDirectionUp' onclick='VacancySortBySalaryASC()'></label>
                                                        <label class='SortDirectionDown' onclick='VacancySortBySalaryDESC()'></label>
                                                    </th>
                                                    <th>Operation</th>
                                                </tr>
                                                ".$tableContent."
                                            </table>
                                            <input type='button' class='SeekerListButton' value='Favorite List' onclick='GetFavoriteList()'>
                                            <a>第".$PageNumber."頁/共".$MaxPage."頁</a>
                                            <a>到第</a>
                                            <input type='number' id='JumpPageNumber'>
                                            <a>頁</a>
                                            <input type='button' id='JumpPageButton' value='跳轉' onclick='VacancyJumpPage()'>
                                            <input type='hidden' id='PageNumber' value=$PageNumber>
                                        </div>
                                    </div>
                                ";
                    }
                    break;
            }
        }
        else
        {
            $table = 
            "
                <div class='VacancyTable' name='VacancyTable' id='VacancyTable'>
                    <div class='TableTitle'>
                        <input type='button' class='PrePageButton' value='<' onclick='VacancyPrePage()'>
                        Job Vacancy
                        <input type='button' class='NextPageButton' value='>' onclick='VacancyNextPage()'>
                    </div>
                    <div id='JobTable'>
                        <table class='JobTable'>
                            ".VacancySearchBar($Database)."
                            <tr class='JobTableSchema'>
                                <th onclick='VacancySortByID()'>ID</th>
                                <th>Occupation</th>
                                <th>Location</th>
                                <th>Work Time</th>
                                <th>Education Require</th>
                                <th>Minimum of Working Experience</th>
                                <th>Salary Per Month
                                    <label class='SortDirectionUp' onclick='VacancySortBySalaryASC()'></label>
                                    <label class='SortDirectionDown' onclick='VacancySortBySalaryDESC()'></label>
                                </th>
                            </tr>
                            ".$tableContent."
                        </table>
                        <a>第".$PageNumber."頁/共".$MaxPage."頁</a>
                        <a>到第</a>
                        <input type='number' id='JumpPageNumber'>
                        <a>頁</a>
                        <input type='button' id='JumpPageButton' value='跳轉' onclick='VacancyJumpPage()'>
                        <input type='hidden' id='PageNumber' value=$PageNumber>
                    </div>
                </div>
            ";
        }
        return $table;
    }
?>
