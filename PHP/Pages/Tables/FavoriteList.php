<?php
    function FavoriteList($Database)
    {
        if(!isset($_SESSION["Identity"])||$_SESSION["Identity"]!="seeker")
        {
            return "<div class='alert'>身分錯誤</div>";
        }
        $result=$Database->prepare("SELECT recruit.id,occupation.occupation,location.location,recruit.working_time,
                                    recruit.education,recruit.experience,recruit.salary  
                                    FROM favorite join recruit join occupation join location 
                                    WHERE favorite.user_id=:seekerID and favorite.recruit_id=recruit.id and 
                                    recruit.occupation_id=occupation.id and recruit.location_id=location.id");
        $result->bindParam(":seekerID",$_SESSION["ID"]);
        $result->execute();
        $tableContent="";
        $i=0;
        while($row=$result->fetch())
        {
            $tableContent .= 
                "
                    <tr class='JobTableRow".($i%2)."'>
                        <td id='Favorite$i'>".$row["id"]."</td>
                        <td>".$row["occupation"]."</td>
                        <td>".$row["location"]."</td>
                        <td>".$row["working_time"]."</td>
                        <td>".$row["education"]."</td>
                        <td>".$row["experience"]."</td>
                        <td>".$row["salary"]."</td>
                        <td>
                            <input type='button' class='DeleteButton' value='Delete' onclick='DeleteFavorite(\"Favorite$i\",this)'>
                        </td>
                    </tr>    
                ";
            $i++;
        }
        $table = 
            "
                <div class='JobSeekerTableFull' name='FavoriteList'>
                    <div class='TableTitle'>Favorite List</div>
                    <div id='FavoriteList'>
                        <table class='JobTable'>
                            <tr class='JobTableSchema'>
                                <th>ID</th>
                                <th>Occupation</th>
                                <th>Location</th>
                                <th>Work Time</th>
                                <th>Minimum of Working Experience</th>
                                <th>Salary Per Month</th>
                                <th>Operation</th>
                            </tr>
                            ".$tableContent."
                        </table>
                        <input type='button' class='SeekerListButton' value='Back to Job Vacancy' onclick='Index()'>
                    </div>
                </div>
            ";
        return $table;
    }
?>
