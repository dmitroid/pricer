{extends file="user_layout.tpl"}
{block name=body}
    {if $messages}
        {foreach $messages as $message}
            <span class="message">{$message}</span>
        {/foreach}
    {/if}

    <h3>Create Template</h3>
    <a href="/userpanel">&#8592; Back to your templates</a>
    <form action="/userpanel/savenewtemplate" method="post">
        <div class="form-group row">
            <div class="col-sm-4">
        <h4>Template Name</h4>
        <input type="text" name="name" class="template-name form-control" placeholder="Enter your template name" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4">
                <h4>Template Pricelists</h4>
                <ul id="price-list-ul">
                    <li id="price-list-li">
                        <input type="text" name="pricelist[]" class="form-control" placeholder="Enter pricelist name" required/>
                    </li>
                </ul>
            </div>
        </div>
        <span id="add-button">
            <input type="button" name="add_pricelist" value="Add +" class="btn btn-primary" onclick="addPricelist()"/>
        </span>
        <br>
        <br>
        <input type="submit" class="btn btn-success col-sm-4" value="Save">
    </form>

    <script>
        function addPricelist()
        {
            var txt1 = "<li id=\"price-list-li\"><input type=\"text\" name=\"pricelist[]\" class=\"form-control\" placeholder=\"Enter pricelist name\" required/></li>";
            $("#price-list-ul").append(txt1);
        }

    </script>
{/block}
