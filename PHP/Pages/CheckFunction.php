<?php
    function EducationCheckError($education)
    {
        return $education!="Graduate School"&&$education!="Undergraduate School"&&
               $education!="Senior High School"&&$education!="Junior High School"&&
               $education!="Elementary School";
    }

    function WorkTimeCheckError($workTime)
    {
        return $workTime!="Morning"&&$workTime!="Afternoon"&&
               $workTime!="Night"&&$workTime!="Midnight";
    }

    function WorkingExperienceCheckError($workingExperience)
    {
        return $workingExperience!="0 Year"&&$workingExperience!="1 Year"&&
               $workingExperience!="2 Years"&&$workingExperience!="3 Years"&&
               $workingExperience!="4 Years"&&$workingExperience!="5+ Years";
    }

    function WorkingExperienceTranslate($workingExperience)
    {
        $translateTable["0 Year"]=0;
        $translateTable["1 Year"]=1;
        $translateTable["2 Years"]=2;
        $translateTable["3 Years"]=3;
        $translateTable["4 Years"]=4;
        $translateTable["5+ Years"]=5;
        return $translateTable[$workingExperience];
    }
?>
