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

          function valconpass(password, confirm_password){
            if( password === confirm_password){
                return true;
            } else {
                return false;
            }
          }

          function valfile(file){
            if (!file) {
                return true;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (file && allowedTypes.includes(file.type)) {
                return true;
            } else {
                return false;
            }
          }
          
          function clearErrors(){
            document.getElementById('emailError').innerText = '';
            document.getElementById('passwordError').innerText = '';
            document.getElementById('confirmPasswordError').innerText = '';
            document.getElementById('fileError').innerText = '';
          }

          function valForm(event){
            clearErrors();
            event.preventDefault();

            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');
            const confirm_password = document.getElementsByName('confirm_password')[0].value;
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            const fileInput = document.getElementsByName('picture')[0];
            const fileError = document.getElementById('fileError');

            const file = fileInput.files[0];

            if (!valemail(email)) {
                document.getElementById('emailError').innerText = 'Invalid email format.';
                return;
            }

            if (!valpass(password)) {
                document.getElementById('passwordError').innerText = 'Password must be at least 6 characters long.';
                return;
            }

            if (!valconpass(password, confirm_password)) {
                document.getElementById('confirmPasswordError').innerText = 'Passwords do not match.';
                return;
            }

            if (!valfile(file)) {
                document.getElementById('fileError').innerText = 'Invalid file type. Please upload an image file.';
                return;
            }

            
            document.getElementById('signupForm').submit();
           
          } 