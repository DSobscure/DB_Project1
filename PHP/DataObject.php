<?php
    function WorkTime()
    {
        $workTime[]="Morning";
        $workTime[]="Afternoon";
        $workTime[]="Night";
        $workTime[]="Midnight";
        return $workTime;
    }
    $workTime=WorkTime();

    function EducationRequired()
    {
        $educationRequired[]="Graduate School";
        $educationRequired[]="Undergraduate School";
        $educationRequired[]="Senior High School";
        $educationRequired[]="Junior High School";
        $educationRequired[]="Elementary School";
        return $educationRequired;
    }
    $educationRequired=EducationRequired();

    function WorkingExperience()
    {
        $workingExperience[]="0 Year";
        $workingExperience[]="1 Year";
        $workingExperience[]="2 Years";
        $workingExperience[]="3 Years";
        $workingExperience[]="4 Years";
        $workingExperience[]="5+ Years";
        return $workingExperience;
    }
    $workingExperience=WorkingExperience();

    function SalaryRange()
    {
        $salaryRange[]="< 10000";
        $salaryRange[]="< 20000";
        $salaryRange[]="< 30000";
        $salaryRange[]="< 40000";
        $salaryRange[]="< 50000";
        $salaryRange[]="< 60000";
        $salaryRange[]="> 10000";
        $salaryRange[]="> 20000";
        $salaryRange[]="> 30000";
        $salaryRange[]="> 40000";
        $salaryRange[]="> 50000";
        $salaryRange[]="> 60000";
        return $salaryRange;
    }
    $salaryRange=SalaryRange();
?>
