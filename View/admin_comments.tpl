
{extends file="admin_layout.tpl"}
{block name=body}

    <h3>List of comments</h3>
    <table class="table table-bordered" >
        <thead>
        <th style="width: 5%; text-align: center;">ID</th>
        <th style="width: 15%; text-align: center;">Date</th>
        <th>Comment</th>
        <th style="width: 10%;">User</th>
        <th style="width: 10%; text-align: center;">Delete</th>
        </thead>
        <tbody>
        {foreach $comments as $comment}
            <tr>
                <td style="text-align: center;">{$comment['id']}</td>
                <td>{$comment['date_time']}</td>
                <td>{$comment['comment']}</td>
                <td>{$comment['user_name']}</td>
                <td style="text-align: center;"><a href="/admin/comment/remove/{$comment['id']}?a=1" type="submit" class="btn btn-danger">Delete</a></td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <nav aria-label="Page navigation" style="text-align: center;">
        <div id="hidden-pagination" style="border: 3px #ccc solid; width: 116px; margin-left: 514px;position: relative; display: none;">
            <ul class="pagination" style="margin: 5px;">
                {for $i=1 to $pagination['pagesCount']}
                    <li><a href="/admin/comments/index/{$i}">{$i}</a></li>
                {/for}
            </ul>
        </div>
        <div>
            <ul class="pagination" style="margin: 5px;">
                <li><a href="/admin/comments/index/1">1</a></li>
                <li><a href="javascript: showPagination();">...</a></li>
                <li><a href="/admin/comments/index/{$pagination['pagesCount']}">{$pagination['pagesCount']}</a></li>
            </ul>
        </div>

        <ul class="pagination" style="margin: 0px;">
            <li>
                <a {if ($pagination['prev'] != 0)} href="/admin/comments/index/{$pagination['prev']}" {/if} aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li><a>{$pagination['currentPage']}</a></li>
            <li>
                <a {if ($pagination['next'] != 0)} href="/admin/comments/index/{$pagination['next']}" {/if} aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
{/block}
