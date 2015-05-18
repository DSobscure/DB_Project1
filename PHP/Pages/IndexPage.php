<?php
    function IndexPage($Database)
    {
        return 
            ATHeader().
            ATContent($Database);
    }

    function ATHeader()
    {
        $page = 
            "
                <div data-role='header' onclick='Index()'>";
                if(isset($_SESSION["UserAccount"]))
                {
                    $page.="<label class='NameLable'>".$_SESSION["UserAccount"]."</label>";
                }
        $page .="
                    Job Seeker
                ";
                if(isset($_SESSION["UserAccount"]))
                {
                    $page.="<input type='button' class='LogoutButton' onclick='Logout(this)' value='Logout'>";
                }
        $page.="</div>
            ";
        return $page;
    }
    function ATContent($Database)
    {
        return
            (isset($_SESSION["UserAccount"]))?
            "
                <div data-role='content'>
                    <div id='content' class='content'>
                            <div id='main'>
                            ".VacancyTable($Database)."
                            </div>
                    </div>                    
                </div>
            "
            :
            "
                <div data-role='content'>
                    <div id='content' class='content'>
                            ".LoginForm()."
                            <div id='main'>
                                ".VacancyTable($Database)."
                            </div> 
                    </div>                    
                </div>
            "
            ;
    }

    function LoginForm()
    {
        return
            "
                <div class='loginForm' name='loginForm'>
                    <div class='Tab' id='EmployerTab' style='border-style: inset;background-color: #CCC9C9' onclick='EmployerIdentitySelect()'>Employer</div>
                    <div class='Tab' id='JobSeekerTab' onclick='JobSeekerIdentitySelect()'>Job Seeker</div>
                    <div class='inlineObject'>
                        <div>
                            <input type='text' id='Account' name='Account' class='formItem' placeholder='Account'>
                            <input type='password' id='Password' name='Password' class='formItem' placeholder='Password'>
                            <input type='hidden' id='Identity' name='Identity' value='employer'>
                        </div>
                        <div>
                            <input type='button' class='formItemButtonLogin' value='Log In' onclick='Login(this)'>
                            <input type='button' class='formItemButtonRegister' value='Sign Up Now' onclick='Register()'>
                        </div>
                    </div>
                </div>
            ";
    }
?>
