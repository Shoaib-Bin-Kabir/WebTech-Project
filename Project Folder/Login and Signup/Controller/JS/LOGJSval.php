<?php
?>

   function valemail(email){
            const emailpattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailpattern.test(email);    
          }
  function valpass(password){
            if (password.length < 6) {
                return false;
            }
            return true;
          }

   function clearErrors(){
            document.getElementById('emailError').innerText = '';
            document.getElementById('passwordError').innerText = '';
          }    


    function valloginForm(event){
            clearErrors();
            event.preventDefault();

            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');

            let valid = true;

            if (!valemail(email)) {
                emailError.innerText = 'Invalid email format.';
                valid = false;
            }

            if (!valpass(password)) {
                passwordError.innerText = 'Password must be at least 6 characters long.';
                valid = false;
            }

            if (!valid) {
                return;
            }

            
            document.getElementById('loginForm').submit();
          }

