<?php
    function MyJobTable($Database)
    {
        if(!isset($_SESSION["Identity"])||$_SESSION["Identity"]!="employer")
        {
            return "<div class='alert'>身分不合法</div>";
        }
        $workingExperience[0]="0 Year";
        $workingExperience[1]="1 Year";
        $workingExperience[2]="2 Years";
        $workingExperience[3]="3 Years";
        $workingExperience[4]="4 Years";
        $workingExperience[5]="5+ Years";


        $table="";

        $table .="<div class='TableTitle'>Who Applies for Your Job</div>";
        $result=$Database->prepare("select recruit.id,occupation.occupation,location.location,recruit.working_time,
                                       recruit.education,recruit.experience,recruit.salary
                                       from recruit,occupation,location 
                                       where recruit.employer_id=:employerID and recruit.occupation_id=occupation.id
                                       and recruit.location_id=location.id");
        $result->bindParam(":employerID",$_SESSION["ID"]);
        $result->execute();
        $index=0;
        while($row=$result->fetch())
        {
            $table .="
                    <table>";
            $table .=
                "
                    <input type='hidden' id='Job$index' value='".$row["id"]."'>
                    <tr class='MyJobRow'>
                        <td style='width:3cm;padding-left: 10px;'>".$row["occupation"]."</td>
                        <td style='width:3cm;padding-left: 10px;'>".$row["location"]."</td>
                        <td style='width:3cm;padding-left: 10px;'>".$row["working_time"]."</td>
                        <td style='width:5cm;padding-left: 10px;'>".$row["education"]."</td>
                        <td style='width:3cm;padding-left: 10px;'>".$workingExperience[$row["experience"]]."</td>
                        <td style='width:3cm;padding-left: 10px;'>".$row["salary"]."</td>
                        <td style='width:6cm;padding-left: 10px;'></td>
                        <td style='width:3cm;padding-left: 10px;'></td>
                        <td style='width:2cm;padding-left: 10px;'></td>
                    </tr>
                ";
            $queryResult=$Database->prepare("SELECT user.id as seeker_id,specialty.specialty as specialty  
                                           FROM user_specialty,specialty,user,application 
                                           WHERE user_specialty.specialty_id=specialty.id and user_specialty.user_id=user.id
                                           and application.recruit_id=:recruitID and application.user_id=user.id");
            $queryResult->bindParam(":recruitID",$row["id"]);
            $queryResult->execute();
            while($row2=$queryResult->fetch())
            {
                $specialties[$row2["seeker_id"]][] = $row2["specialty"];
            }
            $queryResult=$Database->prepare("SELECT user.id,user.account,user.gender,user.age,user.education,user.expected_salary,
                                             user.phone,user.email from user,application 
                                             where application.recruit_id=:recruitID and application.user_id=user.id");
            $queryResult->bindParam(":recruitID",$row["id"]);
            $queryResult->execute();
            $i=0;
            while($row2=$queryResult->fetch())
            {
                $specialty = "<select>";
                if(isset($specialties[$row2["id"]]))
                {
                    foreach($specialties[$row2["id"]] as $item)
                    {
                        $specialty .= "<option value='$item'>$item</option>";
                    }
                }
                $specialty .= "</select>";
                $table .=
                    "
                        <tr class='SeekerRow".($i%2)."'>
                            <td style='width:3cm;padding-left: 10px;'>".$row2["account"]."</td>
                            <td style='width:3cm;padding-left: 10px;'>".$row2["gender"]."</td>
                            <td style='width:3cm;padding-left: 10px;'>".$row2["age"]."</td>
                            <td style='width:5cm;padding-left: 10px;'>".$row2["education"]."</td>
                            <td style='width:3cm;padding-left: 10px;'>".$row2["expected_salary"]."</td>
                            <td style='width:3cm;padding-left: 10px;'>".$row2["phone"]."</td>
                            <td style='width:6cm;padding-left: 10px;'>".$row2["email"]."</td>
                            <td style='width:3cm;padding-left: 10px;'>".$specialty."</td>
                            <td style='width:2cm;padding-left: 10px;'><input type='button' class='HireButton' value='Hire' onclick='Hire(\"Job$index\",this)'></td>
                        </tr>
                    ";
                $index++;
                $i++;
            }
            $table .="</table>
                     ";
        }

        $table .="<input type='button' class='SeekerListButton' value='Back to Job Vacancy' onclick='Index()'>";

        return $table;
    }
?>
