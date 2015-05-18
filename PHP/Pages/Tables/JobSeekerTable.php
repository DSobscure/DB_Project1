<?php
    function JobSeekerTable($Database,$PageNumber=1)
    {
        if(!isset($_SESSION["Identity"])||$_SESSION["Identity"]!="employer")
        {
            return "<div class='alert'>身分錯誤</div>";
        }
        $queryResult=$Database->query("select count(*)as count from user");
        $row=$queryResult->fetch();
        $MaxPage=ceil($row["count"]/10);
        if($PageNumber<1)
            $PageNumber=1;
        if($PageNumber>$MaxPage)
            $PageNumber=$MaxPage;
        $startIndex=(($PageNumber>=1)?$PageNumber-1:0)*10;

        $queryResult=$Database->query("SELECT user.id as seeker_id,specialty.specialty as specialty  
                                       FROM user_specialty JOIN specialty JOIN user 
                                       WHERE user_specialty.specialty_id=specialty.id and user_specialty.user_id=user.id");
        while($row=$queryResult->fetch())
        {
            $specialties[$row["seeker_id"]][] = $row["specialty"];
        }
        $queryResult=$Database->query("SELECT id,account,gender,age,education,expected_salary,phone,email FROM user
                                       limit $startIndex,10");
        $tableContent="";
        for($i=0;$i<10;$i++)
        {
            $id=NULL;$name=NULL;$gender=NULL;$age=NULL;$education=NULL;$salary=NULL;$phone=NULL;$email=NULL;$specialty=NULL;

            if($row=$queryResult->fetch())
            {
                $id=$row["id"];
                $name=$row["account"];
                $gender=$row["gender"];
                $age=$row["age"];
                $education=$row["education"];
                $salary=$row["expected_salary"];
                $phone=$row["phone"];
                $email=$row["email"];
                $specialty = "<select>";
                if(isset($specialties[$id]))
                {
                    foreach($specialties[$id] as $item)
                    {
                        $specialty .= "<option value='$item'>$item</option>";
                    }
                }
                $specialty .= "</select>";
            }
            $tableContent .= 
                "
                    <tr class='JobTableRow".($i%2)."'>
                        <td>$id</td>
                        <td>$name</td>
                        <td>$gender</td>
                        <td>$age</td>
                        <td>$education</td>
                        <td>$salary</td>
                        <td>$phone</td>
                        <td>$email</td>
                        <td>$specialty</td>
                    </tr>    
                ";
        }
        $table = 
            "
                <div class='JobSeekerTableFull' name='JobSeekerTable'>
                    <div class='TableTitle'>
                    <input type='button' class='PrePageButton' value='<' onclick='SeekerPrePage()'>
                        Job Seeker List
                    <input type='button' class='NextPageButton' value='>' onclick='SeekerNextPage()'>
                </div>
                <div id='SeekerTable'>
                    <table class='JobTable'>
                        <tr class='JobTableSchema'>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Education</th>
                            <th>Expected Salary</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Specialty</th>
                         </tr>
                         ".$tableContent."
                     </table>
                     <input type='button' class='SeekerListButton' value='Back to Job Vacancy' onclick='Index()'>
                     <a>第".$PageNumber."頁/共".$MaxPage."頁</a>
                     <input type='hidden' id='PageNumber' value=$PageNumber>
                 </div>
             </div>
            ";
        return $table;
    }
?>
