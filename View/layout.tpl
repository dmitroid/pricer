<html>
<head>
    <title>Pricer</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="/View/css/styles.css">

    <script src="/View/js/pagination.js"></script>
    <script src="/View/js/comment.js"></script>
    <script src="/View/js/views.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Pricer</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                {if isset($smarty.session.user.login) && $smarty.session.user.role == 0}
                    <li><a href="/userpanel">User Panel</a></li>
                {/if}
                {if isset($smarty.session.user.login) && $smarty.session.user.role > 0}
                    <li><a href="/adminpanel">Admin Panel</a></li>
                {/if}
                <li><a href="/faq">FAQ</a></li>

            </ul>

            <ul class="nav navbar-nav navbar-right">

                {if !isset($smarty.session.user.login)}
                    <li class=""><a href="/auth/login">Login<span class="sr-only"></span></a></li>
                    <li class=""><a href="/auth/register">Register<span class="sr-only"></span></a></li>
                {else}

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{$smarty.session.user.login}<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/auth/logout">Logout</a></li>
                        </ul>
                    </li>
                {/if}

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container">


    {block name=body}{/block}

<div class="row" style="width: 100%; height: 100px;"></div>
</body>
</html>
