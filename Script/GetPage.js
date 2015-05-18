function Register()
{
    var identity = document.getElementById('Identity').value;
    $.get("PHP/AJAX_GET.php",
            {
                action: "ToRegisterPage",
                Identity: identity
            },
            function (result) { document.body.innerHTML = result;}
         );
}

function Index()
{
    $.get("PHP/AJAX_GET.php", 
            { 
                action: "ToIndexPage"
            },
            function(result){document.body.innerHTML = result;}
         );
}

function GetSeekerList()
{
    $.get("PHP/AJAX_GET.php", 
            { 
                action: "GetSeekerList"
            },
            function(result){document.getElementById("main").innerHTML = result;}
          );
}

function GetFavoriteList()
{
    $.get("PHP/AJAX_GET.php", 
            { 
                action: "GetFavoriteList"
            },
            function(result){document.getElementById("main").innerHTML = result;}
          );
}

function GetMyJobTable()
{
    $.get("PHP/AJAX_GET.php", 
            { 
                action: "GetMyJobTable"
            },
            function(result){document.getElementById("main").innerHTML = result;}
          );
}