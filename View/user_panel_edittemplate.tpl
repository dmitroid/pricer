{extends file="user_layout.tpl"}
{block name=body}
    {if $messages}
        {foreach $messages as $message}
            <span class="message">{$message}</span>
        {/foreach}
    {/if}

    <h3>Edit Template</h3>
    <a href="/userpanel"> &#8592;Back to your templates</a>
    <form action="/userpanel/savetemplate" method="post">

        <h4>Template Name</h4>
        <div class="form-group row">
            <div class="col-sm-4">
        <input type="text" name="name" class="template-name form-control" value="{$template['name']}" placeholder="Enter your template name" required />
        <input type="hidden" name="template_id" value="{$template['id']}"/>
            </div>
        </div>

        <h4>Template Pricelists</h4>
        <div class="form-group row">
            <div class="col-sm-4">
                <ul id="price-list-ul">
                {foreach $template['prices'] as $price}
                    <li class="price-list-li">
                        <span class="price-span price-name">Price list: <strong>{$price['name']}</strong></span><span class="price-span"><a href="/userpanel/editprice/{$price['id']}">Edit</a></span><span class="price-span"><a href="/userpanel/removeprice/{$price['id']}">Remove</a></span>
                        <ul id="fields-list-ul">
                            <li id="field-list-li">
                                <span class="field-header-span"><strong>List of fields</strong></span>
                            </li>
                            {foreach $price['fields'] as $field}
                                <li id="field-list-li">
                                    <span class="price-span">{$field['name']}</span>
                                </li>
                            {/foreach}
                            <li id="field-list-li">
                                <span class="field-header-span"><strong><a href="/userpanel/editprice/{$price['id']}">Add Fields</a></strong></span>
                            </li>
                        </ul>
                    </li>
                {/foreach}

                    <li id="price-list-li" style="display: none;">
                        <input type="text" name="pricelist[]" class="form-control" placeholder="Enter pricelist name" required/>
                    </li>
                    <li id="add-button">
                        <input type="button" name="add_pricelist" value="Add Pricelist" class="btn btn-primary" onclick="addPricelist()"/>
                    </li>
                </ul>
            </div>
        </div>


        <input type="submit" value="Save" class="btn btn-success col-sm-4">
    </form>

    <script>
        function addPricelist()
        {
            $("#price-list-li").show();
            $("#add-button").hide();
        }
    </script>
{/block}
