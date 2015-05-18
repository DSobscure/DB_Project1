function CancleJobInput()
{
    Index();
}

function VacancySortByID()
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "VacancySortByID"
            },
            function (result) { document.getElementById("main").innerHTML = result;}
          );
}

function VacancySortBySalaryASC()
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "VacancySortBySalaryASC"
            },
            function (result) { document.getElementById("main").innerHTML = result;}
          );
}

function VacancySortBySalaryDESC()
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "VacancySortBySalaryDESC"
            },
            function (result) { document.getElementById("main").innerHTML = result;}
          );
}

function VacancyNextPage()
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "VacancyNextPage",
                PageNumber: $('#PageNumber').val()
            },
            function (result) { document.getElementById("main").innerHTML = result;}
          );
}

function VacancyPrePage()
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "VacancyPrePage",
                PageNumber: $('#PageNumber').val()
            },
            function (result) { document.getElementById("main").innerHTML = result;}
          );
}

function SeekerPrePage()
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "SeekerPrePage",
                PageNumber: $('#PageNumber').val()
            },
            function (result) { document.getElementById("content").innerHTML = result;}
          );
}

function SeekerNextPage()
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "SeekerNextPage",
                PageNumber: $('#PageNumber').val()
            },
            function (result) { document.getElementById("content").innerHTML = result;}
          );
}

function VacancyJumpPage()
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "VacancyJumpPage",
                PageNumber: $('#JumpPageNumber').val()
            },
            function (result) { document.getElementById("main").innerHTML = result;}
          );
}

function VacancySearch(caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    var salaryRange = $('#salaryRangeSearch').val();
    var sign = salaryRange[0];
    var number = salaryRange.substring(2);
    $.get("PHP/AJAX_GET.php",
            {
                action: "VacancySearch",
                Occupation: $('#occupationSearch').val(),
                Location: $('#locationSearch').val(),
                WorkTime: $('#workTimeSearch').val(),
                Education: $('#educationSearch').val(),
                WorkingExperience: $('#workingExperienceSearch').val(),
                SalaryRangeSign: sign,
                SalaryRangeNumber: number
            },
            function (result) { document.getElementById("main").innerHTML = result;}
          );
}

function ClearSearchCondition()
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "ClearSearchCondition"
            },
            function (result) { document.getElementById("main").innerHTML = result;}
          );
}