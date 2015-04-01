<?php
$basePath = dirname(__FILE__) . '/..';
Yii::$classMap = array(
    'YiinfiniteScroller' => $basePath . '/extensions/yiinfinite-scroll/YiinfiniteScroller.php',
    'ContentMetadata' => $basePath . '/models/ContentMetadata.php',
    'UserRoles' => $basePath . '/models/UserRoles.php',
    'UserMetadata' => $basePath . '/models/UserMetadata.php',
    'ContentTypes' => $basePath . '/models/ContentTypes.php',
    'Events' => $basePath . '/models/Events.php',
    'Comments' => $basePath . '/models/Comments.php',
    'Content' => $basePath . '/models/Content.php',
    'Configuration' => $basePath . '/models/Configuration.php',
    'CategoriesMetadata' => $basePath . '/models/CategoriesMetadata.php',
    'Users' => $basePath . '/models/Users.php',
    'ActivationForm' => $basePath . '/models/forms/ActivationForm.php',
    'LoginForm' => $basePath . '/models/forms/LoginForm.php',
    'RegisterForm' => $basePath . '/models/forms/RegisterForm.php',
    'InviteForm' => $basePath . '/models/forms/InviteForm.php',
    'InvitationForm' => $basePath . '/models/forms/InvitationForm.php',
    'ProfileForm' => $basePath . '/models/forms/ProfileForm.php',
    'PasswordResetForm' => $basePath . '/models/forms/PasswordResetForm.php',
    'EmailChangeForm' => $basePath . '/models/forms/EmailChangeForm.php',
    'ForgotForm' => $basePath . '/models/forms/ForgotForm.php',
    'SocialSettings' => $basePath . '/models/settings/SocialSettings.php',
    'AnalyticsSettings' => $basePath . '/models/settings/AnalyticsSettings.php',
    'GeneralSettings' => $basePath . '/models/settings/GeneralSettings.php',
    'ThemeSettings' => $basePath . '/models/settings/ThemeSettings.php',
    'EmailSettings' => $basePath . '/models/settings/EmailSettings.php',
    'Categories' => $basePath . '/models/Categories.php',
    'CategoriesController' => $basePath . '/controllers/CategoriesController.php',
    'ProfileController' => $basePath . '/controllers/ProfileController.php',
    'ContentController' => $basePath . '/controllers/ContentController.php',
    'SiteController' => $basePath . '/controllers/SiteController.php',
);
