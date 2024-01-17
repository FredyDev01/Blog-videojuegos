<?php
    if($maxPage): 
?>
        <div id="pagination" class="mx-auto">
            <ul class="flex-row gap-xxs">
                <li>
                    <button
                        class="btn btnXs roundedXs btnOutlinePrimary"
                        data-page="<?= $currentPage - 1 ?>"
                        <?= $currentPage === 1 ? 'disabled' : '' ?>
                    >
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                </li>                
                <?php        
                    $leftMore = $currentPage > 4; 
                    $rigthMore = $currentPage <= ($maxPage - 4);
                    $rangePage = [];
                    if($leftMore && $rigthMore) {
                        $rangePage = range(($currentPage - 1), ($currentPage + 1));                
                    }else {
                        $start =  $leftMore ? $maxPage - 4 : 1;
                        $end = $currentPage > 5 || $maxPage < 5 ? $maxPage : 5;
                        $rangePage = range($start, $end);
                    }
                    if($leftMore):
                ?>
                    <li>
                        <button 
                            class="btn btnXs roundedXs btnOutlinePrimary" 
                            data-page="1"
                        >
                            1
                        </button>
                    </li>
                    <li>
                        <button 
                            class="btn btnXs roundedXs btnOutlinePrimary" 
                            data-page="<?= $currentPage - 3 ?>"
                        >
                            ...
                        </button>
                    </li>
                <?php
                    endif;
                    foreach($rangePage as $page):
                        $isActive = intval($currentPage) === intval($page);
                ?>
                        <li>
                            <button 
                                class="btn btnXs roundedXs btnOutlinePrimary <?= $isActive ? 'active' : '' ?>" 
                                data-page="<?= $page ?>"
                            >
                                <?= $page ?>
                            </button>
                        </li>
                <?php
                    endforeach;
                    if($rigthMore):
                ?>
                    <li>
                        <button
                            class="btn btnXs roundedXs btnOutlinePrimary"
                            data-page="<?= $currentPage + 3 ?>"
                        >
                            ...
                        </button>
                    </li>
                    <li>
                        <button
                            class="btn btnXs roundedXs btnOutlinePrimary"
                            data-page="<?= $maxPage ?>"
                        >
                            <?= $maxPage ?>
                        </button>
                    </li>
                <?php
                    endif;
                ?>
                <li>
                    <button
                        class="btn btnXs roundedXs btnOutlinePrimary"
                        data-page="<?= $currentPage + 1 ?>"
                        <?= $currentPage === $maxPage ? 'disabled' : '' ?>
                    >
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </li>                
            </ul>
        </div>
<?php
    endif;
?>