{extends file="layout.tpl"}
{block name=body}
    {if $messages}
        {foreach $messages as $message}
            <span class="message">{$message}</span>
        {/foreach}
    {/if}
<div class="login-register">
    <h3>Register</h3>
    <form action="/auth/register" method="POST">
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" class="form-control" name="login" id="login">
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" name="pass" id="pwd">
        </div>
        <button type="submit" class="btn btn-success">Register</button>
        Has account? <a href="/auth"> Login </a>
    </form>

</div>
{/block}
