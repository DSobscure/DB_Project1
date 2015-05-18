<?php
    function RegisterPage($identity,$Database)
    {
        switch($identity)
        {
            case "employer":
                return EmployerRegisterPage();
            case "seeker":
                return SeekerRegisterPage($Database);
            default:
                return "";
        }
    }

    function EmployerRegisterPage()
    {
        return 
            "
                <div class='registerForm'>
                    <div>
                        Employer
                    </div>
                    <form>
                    <div class='registerItem'>
				        <label for='account'>帳號：(長度限制6~20字)</label>
				        <input id='account' name='account' type='text' placeholder='請輸入您的帳號'/>
                    </div>

                    <div class='registerItem'>
				        <label for='password'>密碼：(6個字以上)</label>
				        <input id='password' name='password' type='password' placeholder='請輸入您的密碼'/>
                    </div>

                    <div class='registerItem'>
				        <label for='passwordCheck'>確認密碼：</label>
				        <input id='passwordCheck' name='passwordCheck' type='password' placeholder='請再次輸入您的密碼'/>
                    </div>

                    <div class='registerItem'>
				        <label for='phone'>電話：</label>
				        <input id='phone' name='phone' type='text' placeholder='請輸入您的電話'/>
                    </div>

                    <div class='registerItem'>
				        <label for='email'>電子信箱：</label>
				        <input id='email' name='email' type='email' placeholder='請輸入您的電子信箱'/>
                    </div>

                    <div class='registerItem'>
				        <input type='button' value='確認送出' onclick='EmployerRegister(this)'>
                        <input type='reset' value='重新輸入'>
                    </div>
                    </form>
                </div>
            ";
    }

    function SeekerRegisterPage($Database)
    {
        $pageContent=
            "
                <div class='registerForm'>
                    <div>
                        Job Seeker
                    </div>
                    <form>
                    <div class='registerItem'>
				        <label for='account'>帳號：(長度限制6~20字)</label>
				        <input id='account' name='account' type='text' placeholder='請輸入您的帳號'/>
                    </div>

                    <div class='registerItem'>
				        <label for='password'>密碼：</label>
				        <input id='password' name='password' type='password' placeholder='請輸入您的密碼'/>
                    </div>

                    <div class='registerItem'>
				        <label for='passwordCheck'>確認密碼：</label>
				        <input id='passwordCheck' name='passwordCheck' type='password' placeholder='請再次輸入您的密碼'/>
                    </div>

                    <div class='registerItem'>
				        <label for='phone'>電話：</label>
				        <input id='phone' name='phone' type='text' placeholder='請輸入您的電話'/>
                    </div>

                    <div class='registerItem'>
				        <label for='gender'>性別：</label>
                        <select id='gender'>
                            <option value='male'>Male</option>
                            <option value='female'>Female</option>
                        </select>
                    </div>

                    <div class='registerItem'>
				        <label for='age'>年齡：</label>
				        <input id='age' name='age' type='number' placeholder='請輸入您的年齡'/>
                    </div>

                    <div class='registerItem'>
				        <label for='email'>電子信箱：</label>
				        <input id='email' name='email' type='email' placeholder='請輸入您的電子信箱'/>
                    </div>

                    <div class='registerItem'>
				        <label for='salary'>預期的薪水：</label>
				        <input id='salary' name='salary' type='number' placeholder='請輸入您預期的薪水'/>
                    </div>

                    <div class='registerItem'>
				        <label for='education'>學歷：</label>
				        <select id='education'>
                            <option value='Graduate School'>Graduate School</option>
                            <option value='Undergraduate School'>Undergraduate School</option>
                            <option value='Senior High School'>Senior High School</option>
                            <option value='Junior High School'>Junior High School</option>
                            <option value='Elementary School'>Elementary School</optiom>
                        </select>
                    </div>

                    <div class='registerItem'>
				        <label for='specialty'>專長：</label>
                        <div>";
                            $queryResult=$Database->query("SELECT specialty FROM specialty");
                            while($row=$queryResult->fetch())
                            {
                                $pageContent .= 
                                    "<div class='inlineCheckBox'>
                                        <input name='specialty' type='checkbox' value='".$row["specialty"]."'>".$row["specialty"]." "."
                                     </div>";
                            }
        $pageContent .=
                        "</div>
                    </div>

                    <div class='registerItem'>
				        <input type='button' value='確認送出' onclick='SeekerRegister(this)'>
                        <input type='reset' value='重新輸入'>
                    </div>
                    </form>
                </div>
            ";
        return $pageContent;
    }
?>