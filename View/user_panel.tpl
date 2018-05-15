{extends file="user_layout.tpl"}
{block name=body}

    <h3>Price Templates</h3>
    <table  class="table " style="border: none">
        <thead>
        <th>ID</th>
        <th>Template name</th>
        <th>Create Date</th>
        <th>Update Date</th>
        <th>Generate</th>
        <th>Edit</th>
        <th>Delete</th>
        </thead>
        <tbody>
            {foreach $templates as $index => $template}
                <form action="/update" method="POST">
                <tr id="tr_{$template['id']}">
                        <td>{$template['id']}</td>
                        <input type="hidden" name="id" value="{$template['id']}">
                        <td>{$template['name']}</td>
                        <td>{$template['create_date']}</td>
                        <td>{$template['update_date']}</td>
                        <td><a href="/generate/index/{$template['id']}" class="btn btn-success">Generate</a></td>
                        <td><a href="/userpanel/edittemplate/{$template['id']}" class="btn btn-warning">Edit</a></td>
                        <td><a class="btn btn-danger" onclick="remove({$template['id']})">Delete</a></td>
                    </tr>
                </form>
            {/foreach}
        </tbody>
    </table>
    <h3>Create New Price Template</h3>
    <a href="/userpanel/create"><button type="submit" class="btn btn-success">Create</button></a>

    <script>
        function remove(id) {
            $.ajax({
                url: "/userpanel/remove/" + id,
                type: "DELETE",
                success: function (res) {
                    $("#tr_" + id).hide();
                }
            });
        }
    </script>
{/block}
