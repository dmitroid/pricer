{extends file="user_layout.tpl"}
{block name=body}
    {if $messages}
        {foreach $messages as $message}
            <span class="message">{$message}</span>
        {/foreach}
    {/if}

    <h3>Edit Price</h3>
    <a href="/userpanel/edittemplate/{$price['template_id']}">&#8592; Back to Template</a>
    <form action="/userpanel/saveprice" method="post">

        <h4>Price Name</h4>
        <div class="form-group row">
            <div class="col-sm-4">
        <input type="text" name="name" class="template-name form-control" value="{$price['name']}">
            </div>
        </div>

        <h5>Has Header</h5>
        <div class="form-group row">
            <div class="col-sm-4 form-check">
        <input type="checkbox" name="header" class="form-check-input"  {if $price['header'] == '1'}checked="checked"{else} {/if}>
            </div>
        </div>

        <input type="hidden" name="price_id" value="{$price['id']}"/>
        <input type="hidden" name="template_id" value="{$price['template_id']}"/>

        <h4>Price Fields</h4>
        <table class="table table-bordered" >
            <thead>
            <th style="width: 40%; text-align: center;">Name field</th>
            <th style="width: 20%;">Order in price File</th>
            <th style="width: 20%;">Order in Result Price</th>
            <th >Action</th>
            </thead>
            <tbody>
            {if !$price['fields']}
                <tr>
                    <input type="hidden" name="field-id[unique]" value="unique" />
                    <input type="hidden" name="field-unique[unique]" value="1"/>
                    <input type="hidden" name="field-price[unique]" value="0"/>
                    <td style="text-align: center;"><div class="form-group"><input type="text" name="field-name[unique]" value="Unique" class="form-control" placeholder="Uniqe" required/></div></td>
                    <td style="text-align: center;"><div class="form-group"><input type="text" name="field-order[unique]" value="" class="form-control" required/></div></td>
                    <td style="text-align: center;"><div class="form-group"><input type="text" name="field-order-in-main[unique]" value="" class="form-control" required/></div></td>
                    <td style="text-align: center;"></td>
                </tr>
                <tr>
                    <input type="hidden" name="field-id[price]" value="price" />
                    <input type="hidden" name="field-unique[price]" value="0"/>
                    <input type="hidden" name="field-price[price]" value="1"/>
                    <td style="text-align: center;"><div class="form-group"><input type="text" name="field-name[price]" value="Price" class="form-control" placeholder="Price" required/></div></td>
                    <td style="text-align: center;"><div class="form-group"><input type="text" name="field-order[price]" value="" class="form-control" required/></div></td>
                    <td style="text-align: center;"><div class="form-group"><input type="text" name="field-order-in-main[price]" value="" class="form-control" required/></div></td>
                    <td style="text-align: center;"></td>
                </tr>
            {else}
                {foreach $price['fields'] as $field}
                    <tr>
                        <input type="hidden" name="field-id[{$field['id']}]" value="{$field['id']}" />
                        <input type="hidden" name="field-unique[{$field['id']}]" value="{$field['is_unique']}"  />
                        <input type="hidden" name="field-price[{$field['id']}]" value="{$field['is_price']}" />
                        <td style="text-align: center;"><div class="form-group"><input type="text" name="field-name[{$field['id']}]" value="{$field['name']}" class="form-control" {if $field['is_unique'] == 1} placeholder="Uniqe"{/if} {if $field['is_price'] == 1} placeholder="Price"{/if} required/></div></td>
                        <td style="text-align: center;"><div class="form-group"><input type="text" name="field-order[{$field['id']}]" value="{$field['order']}" class="form-control" required/></div></td>
                        <td style="text-align: center;"><div class="form-group"><input type="text" name="field-order-in-main[{$field['id']}]" value="{$field['order_in_main']}" class="form-control" required/></div></td>
                        <td style="text-align: center;">{if $field['is_price'] == '0' && $field['is_unique'] == '0' }<a href="/userpanel/removefield/{$field['id']}">Remove</a>{/if}</td>
                    </tr>
                {/foreach}
            {/if}

            <tr style="display: none" id="field-block">
                <input type="hidden" name="field-id[0]" value="0" />
                <input type="hidden" name="field-unique[0]" value="0"/>
                <input type="hidden" name="field-price[0]" value="0"/>
                <td style="text-align: center;"><div class="form-group"><input type="text" name="field-name[0]" value="" class="form-control" /></div></td>
                <td style="text-align: center;"><div class="form-group"><input type="text" name="field-order[0]" value="" class="form-control" /></div></td>
                <td style="text-align: center;"><div class="form-group"><input type="text" name="field-order-in-main[0]" value="" class="form-control" /></div></td>
                <td style="text-align: center;"></td>
            </tr>
            </tbody>
        </table>

        <div class="add-filed-block">
            <input type="button" name="add_pricelist" value="Add Field" class="btn btn-primary" onclick="addPricelist()"/>
        </div>

        <input type="submit" value="Save Settings" class="btn btn-success col-sm-4">
    </form>

    <script>
        function addPricelist()
        {
            $("#field-block").show();
            $(".add-filed-block").hide();
        }
    </script>
{/block}
