{extends file="user_layout.tpl"}
{block name=body}
    {if $messages}
        {foreach $messages as $message}
            <span class="message">{$message}</span>
        {/foreach}
    {/if}

    <h3>Generate Template</h3>
    <p><a href="/userpanel">&#8592; Back to your templates</a></p>

    <form action="/generate/show" method="post" enctype="multipart/form-data">
        <div class="form-group row">
            <div class="col-sm-4">
        <p>Template name: <b>{$template['name']}</b></p>
            </div>
        </div>
        <p>Template Pricelists:</p>
        <div class="form-group row">
            <div class="col-sm-4">
        <ol id="price-list-ul">
        {foreach $template['prices'] as $price}
            <li id="price-list-li">
                <label>Please choose pricelist {$price['name']}</label>
                 <input name="price[]" type="file"  />
            </li>
        {/foreach}
        </ol>
            </div>
        </div>
        <input type="hidden" name="template_id" value="{$template['id']}">
        {if $genblock == false}
        <input type="submit" class="btn btn-success" value="Generate">
        {/if}
    </form>
{/block}
