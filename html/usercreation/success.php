<?php include '../../php/handlers/success.php'; ?> 
<!DOCTYPE html> 
<html lang="en">
 <head> 
    <meta charset="UTF-8"> 
    <title>Account Created - ChainLedger</title> 
    <link rel="stylesheet" href="css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> 
</head> 
<body> 
    <!-- Theme toggle -->
    <input type="checkbox" id="theme-toggle" hidden> 
    <div class="page"> 
        <label for="theme-toggle" class="theme-button"> 
            <span class="material-icons light-icon">light_mode</span> 
            <span class="material-icons dark-icon">dark_mode</span> </label> 
            <div class="background-blur"></div> 
            <div class="success-wrapper"> 
                <!-- Success Heading --> <h1 class="success-title">Account Created Successfully!</h1> 
                <!-- Account details --> <div class="success-container"> 
                    <h1 class="success-title2">Account Details</h1> 
                    <div class="account-details"> 
                        <!-- Left --> 
                         <div class="details-left"> 
<p><strong>First Name:</strong> <span><?= htmlspecialchars($user['first_name'] ?? '') ?></span></p>
<p><strong>Last Name:</strong> <span><?= htmlspecialchars($user['last_name'] ?? '') ?></span></p>
<p><strong>Birthdate:</strong> <span><?= htmlspecialchars($user['birthdate'] ?? '') ?></span></p>
<p><strong>Gender:</strong> <span><?= htmlspecialchars($user['gender'] ?? '') ?></span></p>
<p><strong>Security Question:</strong> <span><?= htmlspecialchars($user['security_question'] ?? '') ?></span></p>

                    </div> 
                        <!-- Right --> 
                        <div class="details-right"> 
                            <p class="account-id-row"> 
                                <strong>Account ID:</strong> <span id="accountId"><?= htmlspecialchars($account_id) ?></span> <button type="button" class="copy-btn" onclick="copyAccountId()">📋 Copy</button> 
                               </p> <p><strong>Username:</strong> <span><?= htmlspecialchars($username) ?></span></p> 
                                <p><strong>Password:</strong> <span>********</span></p> <p><strong>
                                Security Answer:</strong> <span><?= htmlspecialchars($user['security_answer']) ?></span></p> 
                            </div> 
                        </div> 
                                <!-- Button --> 
                                 <div class="success-buttons"> 
                                    <a href="login.php" class="back-btn">Back to Log In</a> 
                                </div> 
                            </div> 
                                <!-- Tip --> 
                                <p class="success-tip">💡 Tip: Make sure to remember your credentials — they won’t be shown again!</p> 
                               </div> 
                            </div> 
                            <script src="../js/user.js"></script>
                        </body> 
                        </html>