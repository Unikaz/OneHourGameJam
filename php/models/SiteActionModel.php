<?php

define("ACTION_LOGIN", "login");
define("ACTION_REGISTER", "register");
define("ACTION_LOGOUT", "logout");
define("ACTION_SUBMIT", "submit");
define("ACTION_NEW_JAM", "newjam");
define("ACTION_DELETE_JAM", "deletejam");
define("ACTION_DELETE_ENTRY", "deleteentry");
define("ACTION_SAVE_CONFIG", "saveconfig");
define("ACTION_SAVE_ASSET_EDITS", "saveassetedits");
define("ACTION_DELETE_ASSET", "deleteasset");
define("ACTION_SAVE_JAM_EDITS", "savejamedits");
define("ACTION_SAVE_USER_EDITS", "saveuseredits");
define("ACTION_SAVE_NEW_USER_PASSWORD", "savenewuserpassword");
define("ACTION_CHANGE_PASSWORD", "changepassword");
define("ACTION_SAVE_USER_CHANGES", "saveuserchanges");
define("ACTION_SAVE_NEW_THEME", "savenewtheme");
define("ACTION_DELETE_THEME", "deletetheme");
define("ACTION_DELETE_THEMES", "deletethemes");
define("ACTION_BAN_THEME", "bantheme");
define("ACTION_UNBAN_THEME", "unbantheme");
define("ACTION_DOWNLOAD_DB", "downloaddb");
define("ACTION_ADMIN_VOTE", "adminvote");
define("ACTION_NEW_PLAYFORM", "newplatform");
define("ACTION_EDIT_PLATFORM", "editplatform");
define("ACTION_DELETE_PLATFORM", "deleteplatform");
define("ACTION_UNDELETE_PLATFORM", "undeleteplatform");
define("ACTION_SET_STREAMER", "setstreamer");
define("ACTION_UNSET_STREAMER", "unsetstreamer");

class SiteActionResultModel{
    public $RedirectUrl;
    public $MessageType;
    public $MessageText;

    function __construct($redirectUrl, $messageType, $messageText) {
        $this->RedirectUrl = $redirectUrl;
        $this->MessageType = $messageType;
        $this->MessageText = $messageText;
    }
}

class SiteActionModel{
    public $PostRequest;
    public $PhpFile;
    public $RedirectAfterExecution;
    public $ActionResult;

    function __construct($postRequest, $phpFile, $redirectAfterExecution, $actionResult) {
        $this->PostRequest = $postRequest;
        $this->PhpFile = $phpFile;
        $this->RedirectAfterExecution = $redirectAfterExecution;
        $this->ActionResult = $actionResult;
    }
}

class SiteActionData{
    public $SiteActionModels;

    function __construct(&$configData) {
        $this->SiteActionModels = $this->LoadSiteActions($configData);
    }

    function LoadSiteActions(&$configData){
        AddActionLog("LoadSiteActions");
        StartTimer("LoadSiteActions");
        
        //Actions data: The data in this list governs how site actions are performed
        $actions = Array(
            new SiteActionModel(
                ACTION_LOGIN,
                "php/actions/authentication/login.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_SUCCESS, "Connexion réussie"),
                    "INVALID_PASSWORD_LENGTH" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Taille du mot de passe incorrecte. Il doit être entre ".$configData->ConfigModels[CONFIG_MINIMUM_PASSWORD_LENGTH]->Value." et ".$configData->ConfigModels[CONFIG_MAXIMUM_PASSWORD_LENGTH]->Value." caractères."),
                    "INVALID_USERNAME_LENGTH" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Taille du pseudo incorrecte. Il doit être entre ".$configData->ConfigModels[CONFIG_MINIMUM_USERNAME_LENGTH]->Value." et ".$configData->ConfigModels[CONFIG_MAXIMUM_USERNAME_LENGTH]->Value." caractères."),
                    "USER_DOES_NOT_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Ce pseudo n'existe pas.<br>Voulez-vous <a href='?".GET_PAGE."=".PAGE_REGISTER."'>créer un compte</a> ?"),
                    "INCORRECT_PASSWORD" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Mauvais pseudo/mot de passe."),
                )
            ),
            new SiteActionModel(
                ACTION_REGISTER,
                "php/actions/authentication/register.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_SUCCESS, "Connexion réussie"),
                    "INVALID_PASSWORD_LENGTH" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_REGISTER, MESSAGE_WARNING, "Taille du mot de passe incorrecte. Il doit être entre ".$configData->ConfigModels[CONFIG_MINIMUM_PASSWORD_LENGTH]->Value." et ".$configData->ConfigModels[CONFIG_MAXIMUM_PASSWORD_LENGTH]->Value." caractères."),
                    "INVALID_USERNAME_LENGTH" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_REGISTER, MESSAGE_WARNING, "Taille du pseudo incorrecte. Il doit être entre ".$configData->ConfigModels[CONFIG_MINIMUM_USERNAME_LENGTH]->Value." et ".$configData->ConfigModels[CONFIG_MAXIMUM_USERNAME_LENGTH]->Value." caractères."),
                    "USERNAME_ALREADY_REGISTERED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_REGISTER, MESSAGE_WARNING, "Ce pseudo existe déjà.<br>Voulez-vous <a href='?".GET_PAGE."=".PAGE_LOGIN."'>vous connecter</a> ?"),
                )
            ),
            new SiteActionModel(
                ACTION_LOGOUT,
                "php/actions/authentication/logout.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_SUCCESS, "Déconnexion réussie.")
                )
            ),
            new SiteActionModel(
                ACTION_SUBMIT,
                "php/actions/games/submit.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS_ENTRY_ADDED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_SUCCESS, "Jeu ajouté."),
                    "SUCCESS_ENTRY_UPDATED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_SUCCESS, "Jeu mis à jour."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Vous n'êtes pas connecté."),
                    "MISSING_GAME_NAME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_WARNING, "Votre jeu n'a pas de nom."),
                    "INVALID_GAME_URL" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_WARNING, "L'URL est invalide."),
                    "INVALID_DESCRIPTION" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_WARNING, "Il manque la description."),
                    "INVALID_JAM_NUMBER" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_ERROR, "Numéro de Jam invalide, merci de contacter un administrateur."),
                    "NO_JAM_TO_SUBMIT_TO" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_ERROR, "Il n'y a pas de Jam active à laquelle publier, merci de contacter un administrateur."),
                    "JAM_NOT_STARTED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Cette Jam n'a pas encore commencé."),
                    "INVALID_COLOR" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_WARNING, "La couleur sélectionnée n'est pas disponible."),
                    "SCREENSHOT_NOT_AN_IMAGE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_WARNING, "Le screenshot uploadé n'est pas une image."),
                    "SCREENSHOT_TOO_BIG" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_WARNING, "L'image uploadée est trop grande."),
                    "SCREENSHOT_WRONG_FILE_TYPE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_WARNING, "L'image n'est pas d'un type valide."),
                    "CANNOT_SUBMIT_TO_PAST_JAM" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_ERROR, "Vous ne pouvez publier sur une Jam terminée, merci de contacter un administrateur."),
                    "ENTRY_NOT_ADDED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_SUBMIT, MESSAGE_ERROR, "Une erreur interne a empêché l'ajout de votre jeu, merci de contacter un administrateur."),
                )
            ),
            new SiteActionModel(
                ACTION_NEW_JAM,
                "php/actions/jam/newjam.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_SUCCESS, "Jam programmée."),
                    "INVALID_TIME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_NEW_JAM, MESSAGE_WARNING, "Heure invalide."),
                    "INVALID_DATE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_NEW_JAM, MESSAGE_WARNING, "Date invalide."),
                    "INVALID_THEME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_NEW_JAM, MESSAGE_WARNING, "Thème invalide."),
                    "INVALID_DEFAULT_ENTRY_ICON_URL" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_NEW_JAM, MESSAGE_WARNING, "Icône par défaut est invalide."),
                    "INVALID_JAM_NUMBER" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_NEW_JAM, MESSAGE_ERROR, "Le numéro de Jam est invalide."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_DELETE_JAM,
                "php/actions/jam/deletejam.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_SUCCESS, "Jam supprimée."),
                    "NO_JAMS_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Il n'existe pas de Jam."),
                    "INVALID_JAM_ID" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "ID de la Jam invalide."),
                    "CANNOT_DELETE_JAM" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Impossible de supprimer la Jam."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                )
            ),
            new SiteActionModel(
                ACTION_DELETE_ENTRY,
                "php/actions/games/deleteentry.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_SUCCESS, "Entrée supprimée."),
                    "NO_JAMS_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Il n'existe pas de Jam."),
                    "INVALID_JAM_ID" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "ID de la Jam invalide."),
                    "CANNOT_DELETE_ENTRY" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Impossible de supprimer la Jam."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                )
            ),
            new SiteActionModel(
                ACTION_SAVE_CONFIG,
                "php/actions/config/saveconfig.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_CONFIG, MESSAGE_SUCCESS, "Configuration mise à jour."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NO_CHANGE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_CONFIG, MESSAGE_WARNING, "Aucun changement à la configuration."),
                )
            ),
            new SiteActionModel(
                ACTION_SAVE_ASSET_EDITS,
                "php/actions/asset/saveassetedits.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS_INSERTED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_SUCCESS, "Asset ajouté."),
                    "SUCCESS_UPDATED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_SUCCESS, "Asset mis à jour"),
                    "COULD_NOT_DETERMINE_URL" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_ERROR, "Impossible de trouver l'URL de l'asset. Merci de regarder dans le dossier des assets sur le serveur web."),
                    "UNLOADED_ASSET_TOO_BIG" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_WARNING, "Asset trop gros."),
                    "COULD_NOT_FIND_VALID_FILE_NAME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_ASSETS, MESSAGE_WARNING, "Impossible de trouver un nom de fichier pour l'asset.  Merci de regarder dans le dossier des assets sur le serveur web."),
                    "INVALID_ASSET_TYPE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_WARNING, "Type d'asset invalide."),
                    "ASSET_TYPE_EMPTY" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_WARNING, "Type d'asset manquant."),
                    "INVALID_DESCRIPTION" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_WARNING, "Description invalide."),
                    "INVALID_TITLE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_WARNING, "Titre invalide."),
                    "INVALID_AUTHOR" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_WARNING, "Auteur invalide - ce doit être le pseudo d'un utilisateur enregistré."),
                    "AUTHOR_EMPTY" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_WARNING, "auteur manquant."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                )
            ),
            new SiteActionModel(
                ACTION_DELETE_ASSET,
                "php/actions/asset/deleteasset.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_SUCCESS, "Asset supprimé."),
                    "ASSET_DOES_NOT_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_ASSETS, MESSAGE_ERROR, "asset n'existe pas."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                )
            ),
            new SiteActionModel(
                ACTION_SAVE_JAM_EDITS,
                "php/actions/jam/savejamedits.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_SUCCESS, "Jam mise à jour."),
                    "INVALID_TIME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_WARNING, "Heure invalide."),
                    "INVALID_DATE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_WARNING, "Date invalide."),
                    "INVALID_THEME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_WARNING, "Thème invalide."),
                    "INVALID_DEFAULT_ENTRY_ICON_URL" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_WARNING, "Icône par défaut est invalide."),
                    "INVALID_JAM_NUMBER" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Numéro de Jam invalide"),
                    "INVALID_STREAMER_USERNAME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Le pseudo du streamerest invalide"),
                    "MISSING_STREAMER_USERNAME_OR_TWITCH_USERNAME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Pour les streamers le pseudo et le nom sur Twitch doivent être entrés"),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NO_JAMS_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "Aucune Jam n'existe."),
                    "INVALID_JAM_ID" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_ERROR, "ID de Jam invalide."),
                    "INVALID_COLOR" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_CONTENT, MESSAGE_WARNING, "Couleur invalide."),
                )
            ),
            new SiteActionModel(
                ACTION_SAVE_USER_EDITS,
                "php/actions/user/saveuseredits.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_SUCCESS, "Utilisateur modifié avec succès."),
                    "USER_DOES_NOT_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_ERROR, "L'utilisateur n'existe pas."),
                    "INVALID_ISADMIN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_ERROR, "Invalid IsAdmin."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                )
            ),
            new SiteActionModel(
                ACTION_SAVE_NEW_USER_PASSWORD,
                "php/actions/user/savenewuserpassword.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_SUCCESS, "Mot de passe mis à jour."),
                    "USER_DOES_NOT_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_ERROR, "L'utilisateur n'existe pas."),
                    "INVALID_PASSWORD_LENGTH" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_WARNING, "Taille du mot de passe incorrect. Il doit être entre ".$configData->ConfigModels[CONFIG_MINIMUM_PASSWORD_LENGTH]->Value." et ".$configData->ConfigModels[CONFIG_MAXIMUM_PASSWORD_LENGTH]->Value." caractères."),
                    "PASSWORDS_DONT_MATCH" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_WARNING, "Les mots de passe ne correspondent pas."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                )
            ),
            new SiteActionModel(
                ACTION_CHANGE_PASSWORD,
                "php/actions/user/changepassword.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_USER_SETTINGS, MESSAGE_SUCCESS, "Mot de passe mis à jour."),
                    "USER_DOES_NOT_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_USER_SETTINGS, MESSAGE_ERROR, "L'utilisateur n'existe pas."),
                    "INVALID_PASSWORD_LENGTH" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_USER_SETTINGS, MESSAGE_WARNING, "Taille du mot de passe incorrect. Il doit être entre ".$configData->ConfigModels[CONFIG_MINIMUM_PASSWORD_LENGTH]->Value." et ".$configData->ConfigModels[CONFIG_MAXIMUM_PASSWORD_LENGTH]->Value." caractères."),
                    "PASSWORDS_DONT_MATCH" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_USER_SETTINGS, MESSAGE_WARNING, "Les mots de passe ne correspondent pas."),
                    "INCORRECT_PASSWORD" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_USER_SETTINGS, MESSAGE_WARNING, "L'ancien mot de passe est incorrect"),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_SAVE_USER_CHANGES,
                "php/actions/user/saveuserchanges.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_USER_SETTINGS, MESSAGE_SUCCESS, "Utilisateur mis à jour."),
                    "INVALID_EMAIL" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_USER_SETTINGS, MESSAGE_WARNING, "L'email est invalide."),
                    "INVALID_DISPLAY_NAME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_USER_SETTINGS, MESSAGE_WARNING, "Pseudo incorrecte. Il doit être entre".$configData->ConfigModels[CONFIG_MINIMUM_DISPLAY_NAME_LENGTH]->Value." et ".$configData->ConfigModels[CONFIG_MAXIMUM_DISPLAY_NAME_LENGTH]->Value." caractères."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_SAVE_NEW_THEME,
                "php/actions/theme/savenewtheme.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_THEMES, MESSAGE_SUCCESS, "Thème ajouté."),
                    "THEME_ALREADY_SUGGESTED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_THEMES, MESSAGE_WARNING, "Thème déjà suggéré."),
                    "INVALID_THEME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_THEMES, MESSAGE_WARNING, "Le thème est invalide."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                    "THEME_RECENTLY_USED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_THEMES, MESSAGE_WARNING, "Le thème a été utilisé pour une Jam récente"),
                    "TOO_MANY_THEMES" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_THEMES, MESSAGE_WARNING, "Vous pouvez seulement soumettre ".$configData->ConfigModels[CONFIG_THEMES_PER_USER]->Value." thèmes. Merci de supprimer un thème passé pour en ajouter un nouveau."),
                    "THEME_BANNED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_THEMES, MESSAGE_WARNING, "Ce thème a été banni.")
                )
            ),
            new SiteActionModel(
                ACTION_DELETE_THEME,
                "php/actions/theme/deletetheme.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS_THEMES" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_THEMES, MESSAGE_SUCCESS, "Thème supprimé."),
                    "SUCCESS_MANAGETHEMES" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_SUCCESS, "Thème supprimé."),
                    "INVALID_THEME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_THEMES, MESSAGE_WARNING, "Le thème est invalide."),
                    "THEME_DOES_NOT_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_THEMES, MESSAGE_WARNING, "Le thème n'existe pas."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_DELETE_THEMES,
                "php/actions/theme/deletethemes.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_SUCCESS, "Thèmes supprimés."),
                    "FAILURE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_WARNING, "Un ou plusieurs thèmes n'ont pas pu être supprimés."),
                    "NO_THEMES_SELECTED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_WARNING, "Vous devez sélectionner au moins un thème à supprimer."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_BAN_THEME,
                "php/actions/theme/bantheme.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_SUCCESS, "Thème banni."),
                    "INVALID_THEME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_WARNING, "Thème invalide."),
                    "THEME_DOES_NOT_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_WARNING, "Le thème n'existe pas."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_UNBAN_THEME,
                "php/actions/theme/unbantheme.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_SUCCESS, "Thème dé-banni."),
                    "INVALID_THEME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_WARNING, "Thème invalide."),
                    "THEME_DOES_NOT_EXIST" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MANAGE_THEMES, MESSAGE_WARNING, "Le thème n'existe pas"),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_DOWNLOAD_DB,
                "php/actions/db/downloaddb.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(

                )
            ),
            new SiteActionModel(
                ACTION_ADMIN_VOTE,
                "php/actions/adminvote/adminvote.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCESS_UPDATE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_SUCCESS, "Vote admin mis à jour."),
                    "SUCESS_INSERT" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_SUCCESS, "Vote admin ajouté."),
                    "INVALID_VOTE_TYPE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_USERS, MESSAGE_WARNING, "Type de vote invalide."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_NEW_PLAYFORM,
                "php/actions/platform/newplatform.php",
                "?".GET_PAGE."=".PAGE_EDIT_PLATFORMS,
                Array(
                    "SUCCESS_PLATFORM_ADDED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_SUCCESS, "Plateforme ajoutée."),
                    "ICON_FAILED_TO_UPLOAD" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_ERROR, "Upload de l'icône a échoué."),
                    "ICON_WRONG_FILE_TYPE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "Type d'icône incorrecte. Doit être un png."),
                    "ICON_TOO_BIG" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "L'image est trop grande (max 20kB)."),
                    "ICON_NOT_AN_IMAGE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "L'icône ajoutée n'est pas une image."),
                    "DUPLICATE_PLATFORM_NAME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "La plateforme existe déjà."),
                    "MISSING_PLATFORM_NAME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "Le nom de la plateforme ne doit pas être vide."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_EDIT_PLATFORM,
                "php/actions/platform/editplatform.php",
                "?".GET_PAGE."=".PAGE_EDIT_PLATFORMS,
                Array(
                    "SUCCESS_PLATFORM_EDITED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_SUCCESS, "Plateforme éditée."),
                    "ICON_WRONG_FILE_TYPE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "Type d'icône incorrecte. Doit être un png."),
                    "ICON_TOO_BIG" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "L'image est trop grande (max 20kB)."),
                    "ICON_NOT_AN_IMAGE" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "L'icône ajoutée n'est pas une image."),
                    "UNKNOWN_PLATFORM" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "Plateforme inconnue."),
                    "DUPLICATE_PLATFORM_NAME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "Une autre plateforme avec le même nom existe déjà."),
                    "MISSING_PLATFORM_NAME" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "Le nom de la plateforme ne doit pas être vide."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_DELETE_PLATFORM,
                "php/actions/platform/deleteplatform.php",
                "?".GET_PAGE."=".PAGE_EDIT_PLATFORMS,
                Array(
                    "SUCCESS_PLATFORM_SOFT_DELETED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_SUCCESS, "La plateforme a été 'soft' supprimée."),
                    "UNKNOWN_PLATFORM" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "Plateforme inconnue."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_UNDELETE_PLATFORM,
                "php/actions/platform/undeleteplatform.php",
                "?".GET_PAGE."=".PAGE_EDIT_PLATFORMS,
                Array(
                    "SUCCESS_PLATFORM_RESTORED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_SUCCESS, "Plateforme restaurée."),
                    "UNKNOWN_PLATFORM" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_EDIT_PLATFORMS, MESSAGE_WARNING, "Plateforme inconnue."),
                    "NOT_AUTHORIZED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Seuls les administrateurs peuvent faire cette action."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_SET_STREAMER,
                "php/actions/jam/setstreamer.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_SUCCESS, "Vous êtes maintenant le streamer pour cette Jam."),
                    "STREAMER_ALREADY_SET" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_WARNING, "Un stream est déjà présent pour cette Jam."),
                    "INVALID_JAM_ID" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Jam non trouvée."),
                    "NO_JAM_TO_SUBMIT_TO" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Pas de Jam à laquelle soumettre."),
                    "PERMISSION_DENIED" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Permission manquante."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            ),
            new SiteActionModel(
                ACTION_UNSET_STREAMER,
                "php/actions/jam/unsetstreamer.php",
                "?".GET_PAGE."=".PAGE_MAIN,
                Array(
                    "SUCCESS" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_SUCCESS, "Vous êtes maintenant le streamer pour cette Jam."),
                    "NOT_CURRENTLY_STREAMER" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_WARNING, "You're not the streamer for this jam."),
                    "INVALID_JAM_ID" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Jam non trouvée."),
                    "NO_JAM_TO_SUBMIT_TO" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_MAIN, MESSAGE_ERROR, "Pas de Jam à laquelle soumettre."),
                    "NOT_LOGGED_IN" => new SiteActionResultModel("?".GET_PAGE."=".PAGE_LOGIN, MESSAGE_WARNING, "Non connecté."),
                )
            )
        );

        StopTimer("LoadSiteActions");
        return $actions;
    }
}

?>