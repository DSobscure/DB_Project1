<?php
    function NewJobLine($Database)
    {
        global $workTime,$educationRequired,$workingExperience;
        $queryResult=$Database->query("SELECT occupation FROM occupation");
        while($row=$queryResult->fetch())
        {
            $occupation[]=$row["occupation"];
        }
        $occupationSelect=SelectList($occupation,"occupation");
        $queryResult=$Database->query("SELECT location FROM location");
        while($row=$queryResult->fetch())
        {
            $location[]=$row["location"];
        }
        $locationSelect=SelectList($location,"location");
        $workTimeSelect=SelectList($workTime,"workTime");        
        $educationRequiredSelect=SelectList($educationRequired,"education");       
        $workingExperienceSelect=SelectList($workingExperience,"workingExperience");       
        $line="";
        $line .=
            "
                <td>New</td>
                <td>
                    ".$occupationSelect."
                </td>
                <td>
                    ".$locationSelect."
                </td>
                <td>
                    ".$workTimeSelect."
                </td>
                <td>
                    ".$educationRequiredSelect."
                </td>
                <td>
                    ".$workingExperienceSelect."
                </td>
                <td>
                    <input id='salary' name='salary' type='number'/>
                </td>
                <td>
                    <input type='button' class='SaveButton' value='Save' onclick='SaveNewJob(this)'>
                    <input type='reset' class='CancleButton' value='Cancle' onclick='CancleJobInput()'>
                </td>
            ";

        return $line;
    }

    function SelectList($list,$id,$selectedValue="",$disabledValue="")
    {
        $selectList=
        "<select id='$id'>
            ";
        foreach($list as $item)
        {
            $selectList.="<option value='$item' ".(($item==$selectedValue)?"selected":"")." ".(($item==$disabledValue)?"disabled":"").">$item</option>
                         ";
        }
        $selectList.=
        "</select>";
        return $selectList;
    }

    function EditJobLine($jobID,$Database,$editParameter)
    {
        global $workTime,$educationRequired,$workingExperience;

        $queryResult=$Database->query("SELECT occupation FROM occupation");
        while($row=$queryResult->fetch())
        {
            $occupation[]=$row["occupation"];
        }
        $occupationSelect=SelectList($occupation,"occupationEdit$jobID",$editParameter["Occupation"]);
        $queryResult=$Database->query("SELECT location FROM location");
        while($row=$queryResult->fetch())
        {
            $location[]=$row["location"];
        }
        $locationSelect=SelectList($location,"locationEdit$jobID",$editParameter["Location"]);
        $workTimeSelect=SelectList($workTime,"workTimeEdit$jobID",$editParameter["WorkTime"]);        
        $educationRequiredSelect=SelectList($educationRequired,"educationEdit$jobID",$editParameter["Education"]);       
        $workingExperienceSelect=SelectList($workingExperience,"workingExperienceEdit$jobID",$editParameter["WorkingExperience"]);       
        $line="";
        $line .=
            "
                <td>$jobID</td>
                <td>
                    ".$occupationSelect."
                </td>
                <td>
                    ".$locationSelect."
                </td>
                <td>
                    ".$workTimeSelect."
                </td>
                <td>
                    ".$educationRequiredSelect."
                </td>
                <td>
                    ".$workingExperienceSelect."
                </td>
                <td>
                    <input id='salaryEdit$jobID' name='salaryEdit$jobID' type='number' value='".$editParameter["Salary"]."'/>
                </td>
                <td>
                    <input type='button' class='UpdateButton' value='Update' onclick='UpdateJob(\"$jobID\",this)'>
                    <input type='reset' class='CancleButton2' value='Cancle' onclick='CancleJobInput()'>
                </td>
            ";

        return $line;
    }

    function VacancySearchBar($Database)
    {
        $workTime=WorkTime();
        $educationRequired=EducationRequired();
        $workingExperience=WorkingExperience();
        $salaryRange=SalaryRange();

        $bar="";
        $queryResult=$Database->query("SELECT occupation FROM occupation");
        $occupation[]="Occupation";
        $occupation[]=" ";
        while($row=$queryResult->fetch())
        {
            $occupation[]=$row["occupation"];
        }
        if(isset($_SESSION["SearchCondition_Occupation"]))
        {
            $occupationSelect=SelectList($occupation,"occupationSearch",$_SESSION["SearchCondition_Occupation"],"Occupation");
        }
        else
        {
            $occupationSelect=SelectList($occupation,"occupationSearch","Occupation","Occupation");
        }

        $queryResult=$Database->query("SELECT location FROM location");
        $location[]="Location";
        $location[]=" ";
        while($row=$queryResult->fetch())
        {
            $location[]=$row["location"];
        }
        if(isset($_SESSION["SearchCondition_Location"]))
        {
            $locationSelect=SelectList($location,"locationSearch",$_SESSION["SearchCondition_Location"],"Location");
        }
        else
        {
            $locationSelect=SelectList($location,"locationSearch","Location","Location");
        }

        array_unshift($workTime," ");
        array_unshift($workTime,"Work Time");
        if(isset($_SESSION["SearchCondition_WorkTime"]))
        {
            $workTimeSelect=SelectList($workTime,"workTimeSearch",$_SESSION["SearchCondition_WorkTime"],"Work Time");
        }
        else
        {
            $workTimeSelect=SelectList($workTime,"workTimeSearch","Work Time","Work Time");
        }

        array_unshift($educationRequired," ");
        array_unshift($educationRequired,"Education Required");
        if(isset($_SESSION["SearchCondition_Education"]))
        {
            $educationRequiredSelect=SelectList($educationRequired,"educationSearch",$_SESSION["SearchCondition_Education"],"Education Required"); 
        }
        else
        {
            $educationRequiredSelect=SelectList($educationRequired,"educationSearch","Education Required","Education Required"); 
        }        
        
        array_unshift($workingExperience," ");
        array_unshift($workingExperience,"Working Experience");
        if(isset($_SESSION["SearchCondition_Experience"]))
        {
            $workingExperienceSelect=SelectList($workingExperience,"workingExperienceSearch",$_SESSION["SearchCondition_Experience"],"Working Experience");
        }
        else
        {
            $workingExperienceSelect=SelectList($workingExperience,"workingExperienceSearch","Working Experience","Working Experience");
        }      

        array_unshift($salaryRange," ");
        array_unshift($salaryRange,"Salary Range");
        if(isset($_SESSION["SearchCondition_Salary"]))
        {
            $salaryRangeSelect=SelectList($salaryRange,"salaryRangeSearch",$_SESSION["SearchCondition_Salary"]);
        }
        else
        {
            $salaryRangeSelect=SelectList($salaryRange,"salaryRangeSearch","Salary Range");
        }

        if(isset($_SESSION["SearchCondition"]))
        {
            $bar.="<input type='button' value='Cancle' onclick='ClearSearchCondition()'>";
        }
        $bar.=$occupationSelect;
        $bar.=$locationSelect;
        $bar.=$workTimeSelect;
        $bar.=$educationRequiredSelect;
        $bar.=$workingExperienceSelect;
        $bar.=$salaryRangeSelect;
        $bar.="<input type='button' value='Search' onclick='VacancySearch(this)'>";

        return $bar;
    }
?>
