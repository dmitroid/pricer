{extends file="layout.tpl"}
{block name=body}
    <div class="row">
        <div class="main-image">
            <img src="/files/show/prices" width="300" height="250"/>
        </div>
        <div class="main-image-text">
            <ul>
                <li>Do you have many suppliers with different product positions?</li>
                <li>Do not know how to choose the product at the best price from the supplier?</li>
                <li>You want to work with one price list?</li>
            </ul>

            <p>You need a Pricer <a href="/auth/register" class="btn btn-primary">Try Now</a></p>
        </div>
        <div class="clear"></div>
        <div class="main-text">



            <p>Pricer is a service through which you can combine several prices from your suppliers into one.</p>
            <p>In the resulting price list, only goods with better prices remain.</p>
        </div>

    </div>

<div class="row">
    {if $user_id != 0}
        <div class="leave-comment">
            <h3>Leave your comment about service</h3>
            <form role="form" action="/comments/create" method="post" style="width: 700px;">
                <div class="form-group">
                    <input type="hidden" name="user_name" value="{$smarty.session.user.login}">
                    <input type="hidden" name="user_id" value="{$smarty.session.user.id}">
                    <input type="hidden" name="rating" value="0">
                    <input type="hidden" name="parent_id" value="0">
                    <input type="hidden" name="moderation" value="1">
                    <textarea class="form-control" name="comment" id="text-comment" placeholder="Leave your commentprices"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Send</button>
            </form>
        </div>
    {/if}
    <div class="comments">
        <h4 class="title-comments">Comments our users ({count($comments)})</h4>
        <ul class="media-list">
            {foreach $comments as $comment}
                <li class="media">
                    <div class="media-left">
                        <a href="#">
                            <img class="media-object img-rounded" src="/files/show/555" width="30" alt="">
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-heading">
                            <div class="author">{$comment['user_name']}</div>
                            <div class="metadata">
                                <span class="date">{$comment['date_time']}</span>
                            </div>
                        </div>
                        <div class="media-text text-justify">{$comment['comment']}</div>
                        <div class="footer-comment">
                                    <span class="vote plus" title="Нравится" onclick="addVote({$comment['id']});">
                                        <i class="glyphicon glyphicon-thumbs-up"></i>
                                    </span>
                            <span class="rating" id="rating-{$comment['id']}">
                                        {$comment['rating']}
                                    </span>
                            <span class="vote minus" title="Не нравится" onclick="reduceVote({$comment['id']});">
                                        <i class="glyphicon glyphicon-thumbs-down"></i>
                                    </span>
                            <span class="devide">
                                       |
                                    </span>
                            {if $user_id != 0}
                                <span class="comment-reply">
                                        <a href="javascript: addAnswer({$comment['id']});" class="reply">reply</a>
                                    </span>
                                <div id="answer-{$comment['id']}" style="display: none;">
                                    <form role="form" action="/comments/create" method="post">
                                        <div class="form-group">
                                            <input type="hidden" name="user_name" value="{$smarty.session.user.login}">
                                            <input type="hidden" name="user_id" value="{$smarty.session.user.id}">
                                            <input type="hidden" name="rating" value="0">
                                            <input type="hidden" name="parent_id" value="{$comment['id']}">
                                            <input type="hidden" name="moderation" value="1">
                                            <textarea class="form-control" name="comment" id="text-comment" placeholder="Введите ваш комментраий"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success">Send</button>
                                    </form>
                                </div>
                            {/if}

                            {if array_key_exists('children', $comment)}
                                {foreach $comment['children'] as $child}
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object img-rounded" src="/files/show/555" width="30" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <div class="author">{$child['user_name']}</div>
                                                <div class="metadata">
                                                    <span class="date">{$child['date_time']}</span>
                                                </div>
                                            </div>
                                            <div class="media-text text-justify">{$child['comment']}</div>
                                            <div class="footer-comment">
                                                        <span class="vote plus" title="Нравится" onclick="addVote({$child['id']});">
                                                            <i class="glyphicon glyphicon-thumbs-up"></i>
                                                        </span>
                                                <span class="rating" id="rating-{$child['id']}">
                                                            {$child['rating']}
                                                        </span>
                                                <span class="vote minus" title="Не нравится" onclick="reduceVote({$child['id']});">
                                                            <i class="glyphicon glyphicon-thumbs-down"></i>
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                {/foreach}
                            {/if}
                        </div>
                    </div>
                </li>
            {/foreach}
        </ul>

    </div>
</div>




{/block}
