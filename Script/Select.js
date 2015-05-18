function IdentitySelect(identity)
{
    document.getElementById('Identity').value = identity;
}

function EmployerIdentitySelect()
{
    $("#EmployerTab").css("border-style","inset");
    $("#EmployerTab").css("background-color","#CCC9C9");
    $("#JobSeekerTab").css("border-style","outset");
    $("#JobSeekerTab").css("background-color","#808080");
    IdentitySelect("employer");
}

function JobSeekerIdentitySelect()
{
    $("#JobSeekerTab").css("border-style","inset");
    $("#JobSeekerTab").css("background-color","#CCC9C9");
    $("#EmployerTab").css("border-style","outset");
    $("#EmployerTab").css("background-color","#808080");
    IdentitySelect("seeker");
}