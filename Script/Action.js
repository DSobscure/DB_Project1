function Login(caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.post("PHP/AJAX_POST.php", 
            { 
                action: "Login", 
                Account: $('#Account').val(), 
                Password: $('#Password').val(), 
                Identity: $('#Identity').val()
            },
            function(result){document.body.innerHTML = result;}
          );
}

function Logout(caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.get("PHP/AJAX_GET.php", 
            { 
                action: "Logout"
            },
            function(result){document.body.innerHTML = result;}
          );
}

function EmployerRegister(caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.post("PHP/AJAX_POST.php", 
            { 
                action: "EmployerRegister", 
                Account: $('#account').val(), 
                Password: $('#password').val(), 
                PasswordCheck: $('#passwordCheck').val(), 
                PhoneNumber: $('#phone').val(),
                Email: $('#email').val()
            },
            function(result){document.body.innerHTML = result;}
          );
}

function SeekerRegister(caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    var specialties = [];
    $("input[name=specialty]:checked").each(function () { specialties.push($(this).val());});
    $.post("PHP/AJAX_POST.php", 
            { 
                action: "SeekerRegister", 
                Account: $('#account').val(), 
                Password: $('#password').val(), 
                PasswordCheck: $('#passwordCheck').val(), 
                PhoneNumber: $('#phone').val(),
                Gender: $('#gender').val(),
                Age: $('#age').val(),
                Email: $('#email').val(),
                Salary: $('#salary').val(),
                Education: $('#education').val(),
                Specialty: specialties
            },
            function(result){document.body.innerHTML = result;}
          );
}

function AddNewJob(caller)
{
    $.get("PHP/AJAX_GET.php",
            {
                action: "AddNewJob"
            },
            function (result) { document.getElementById('newJobRow').innerHTML = result;}
          );
}

function SaveNewJob(caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.post("PHP/AJAX_POST.php",
            {
                action: "SaveNewJob",
                Occupation: $('#occupation').val(),
                Location: $('#location').val(),
                WorkTime: $('#workTime').val(),
                Education: $('#education').val(),
                WorkingExperience: $('#workingExperience').val(),
                Salary: $('#salary').val()
            },
            function (result) { document.getElementById("main").innerHTML = result; }
          );
}

function DeleteJob(jobID,caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.post("PHP/AJAX_POST.php",
            {
                action: "DeleteJob",
                JobID: document.getElementById(jobID).innerHTML
            },
            function (result) { document.getElementById("main").innerHTML = result; }
          );
}

function EditJob(row,jobID,caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    var id = jobID.substring(3);
    $.get("PHP/AJAX_GET.php",
            {
                action: "EditJob",
                JobID: document.getElementById(jobID).innerHTML,
                Occupation: document.getElementById("Occupation"+id).innerHTML,
                Location: document.getElementById("Location"+id).innerHTML,
                WorkTime: document.getElementById("WorkTime"+id).innerHTML,
                Education: document.getElementById("Education"+id).innerHTML,
                WorkingExperience: document.getElementById("Experience"+id).innerHTML,
                Salary: document.getElementById("Salary"+id).innerHTML
            },
            function (result) { document.getElementById(row).innerHTML = result;}
          );
}

function UpdateJob(jobID,caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.post("PHP/AJAX_POST.php",
            {
                action: "UpdateJob",
                JobID: jobID,
                Occupation: $('#occupationEdit'+jobID).val(),
                Location: $('#locationEdit'+jobID).val(),
                WorkTime: $('#workTimeEdit'+jobID).val(),
                Education: $('#educationEdit'+jobID).val(),
                WorkingExperience: $('#workingExperienceEdit'+jobID).val(),
                Salary: $('#salaryEdit'+jobID).val()
            },
            function (result) { document.getElementById("main").innerHTML = result; }
          );
}

function AddFavorite(jobID,caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.post("PHP/AJAX_POST.php",
            {
                action: "AddFavorite",
                JobID: document.getElementById(jobID).innerHTML
            },
            function (result) { document.getElementById("main").innerHTML = result; }
          );
}

function DeleteFavorite(favoriteID,caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.post("PHP/AJAX_POST.php",
            {
                action: "DeleteFavorite",
                FavoriteID: document.getElementById(favoriteID).innerHTML
            },
            function (result) { document.getElementById("main").innerHTML = result; }
          );
}

function ApplyJob(jobID,caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.post("PHP/AJAX_POST.php",
            {
                action: "ApplyJob",
                JobID: document.getElementById(jobID).innerHTML
            },
            function (result) { document.getElementById("main").innerHTML = result; }
          );
}

function Hire(jobID,caller)
{
    caller.disabled = true;
    caller.value = "waiting";
    $.post("PHP/AJAX_POST.php",
            {
                action: "Hire",
                JobID: $('#'+jobID).val()
            },
            function (result) { document.getElementById("main").innerHTML = result; }
          );
}