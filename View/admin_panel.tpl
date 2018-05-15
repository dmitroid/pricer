{extends file="admin_layout.tpl"}
{block name=body}
    <h3>Users</h3>
<table  class="table table-bordered">
    <thead>
    <th>ID</th>
    <th>User login</th>
    <th>Delete</th>
    </thead>
    <tbody>
    {foreach $users as $user}
        <tr id="tr_{$user['id']}">
            <td>{$user['id']}</td>
            <td>{$user['login']}</td>
            <td><a class="btn btn-danger" onclick="remove({$user['id']})">Delete</a></td>
        </tr>
    {/foreach}
    </tbody>
</table>
    <nav aria-label="Page navigation" style="text-align: center;">
        <div id="hidden-pagination" style="border: 3px #ccc solid; width: 116px; margin-left: 514px;position: relative; display: none;">
            <ul class="pagination" style="margin: 5px;">
                {for $i=1 to $pagination['pagesCount']}
                    <li><a href="/admin/panel/index/{$i}">{$i}</a></li>
                {/for}
            </ul>
        </div>
        <div>
            <ul class="pagination" style="margin: 5px;">
                <li><a href="/admin/panel/index/1">1</a></li>
                <li><a href="javascript: showPagination();">...</a></li>
                <li><a href="/admin/panel/index/{$pagination['pagesCount']}">{$pagination['pagesCount']}</a></li>
            </ul>
        </div>

        <ul class="pagination" style="margin: 0px;">
            <li>
                <a {if ($pagination['prev'] != 0)} href="/admin/panel/index/{$pagination['prev']}" {/if} aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li><a>{$pagination['currentPage']}</a></li>
            <li>
                <a {if ($pagination['next'] != 0)} href="/admin/panel/index/{$pagination['next']}" {/if} aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

    <script>
        function remove(id) {
            $.ajax({
                url: "/admin/panel/remove/" + id,
                type: "DELETE",
                success: function (res) {
                    $("#tr_" + id).hide();
                }
            });
        }
    </script>
{/block}
