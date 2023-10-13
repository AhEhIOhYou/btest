<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php if (!$arResult["data"]) : ?>
    <h2>
        <?= Loc::getMessage('CURRENCY_EMPTY_DATE') ?>
    </h2>
<?php else: ?>
    <div id="currency-box">
        <table id="currency-list" class="table">
            <thead>
            <tr>
                <th scope="col"><?= Loc::getMessage('CURRENCY_CODE') ?></th>
                <th scope="col"><?= Loc::getMessage('CURRENCY_COURSE') ?></th>
                <th scope="col"><?= Loc::getMessage('CURRENCY_DATE') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($arResult["data"] as $course): ?>
                <tr>
                    <td><?= $course["UF_COURSE_CODE"] ?></td>
                    <td><?= $course["UF_COURSE_COURSE"] ?></td>
                    <td><?= $course["UF_COURSE_DATE"]->format("d.m.Y") ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <nav id="currency-pagination">
            <?php if ($arResult["pages"]): ?>
                <ul class="pagination justify-content-center">
                    <?php foreach ($arResult["pages"]["nav"] as $pageNum): ?>
                        <li class="page-item <?= $arResult["pages"]["active"] == $pageNum ? 'active' : '' ?>">
                            <button class="page-link" data-page="<?= $pageNum ?>">
                                <?= $pageNum ?>
                            </button>
                        </li>
                    <?php endforeach ?>
                </ul>
            <?php endif; ?>
        </nav>
    </div>

<?php endif; ?>
