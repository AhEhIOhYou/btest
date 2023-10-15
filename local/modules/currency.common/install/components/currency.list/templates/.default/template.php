<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php if (!$arResult["data"]) : ?>
    <h2>
        <?= Loc::getMessage('CURRENCY_EMPTY_DATE') ?>
    </h2>
<?php else: ?>
    <div id="currency-box">
        <div class="d-flex justify-content-between my-3">
            <div class="form-group">
                <label for="sortSelect" class="mb-2"><?= Loc::getMessage('CURRENCY_SORT_BY') ?></label>
                <select class="form-control" id="currency-sort-by">
                    <option value="date"><?= Loc::getMessage('CURRENCY_DATE') ?></option>
                    <option value="code"><?= Loc::getMessage('CURRENCY_CODE') ?></option>
                    <option value="course"><?= Loc::getMessage('CURRENCY_COURSE') ?></option>
                </select>
            </div>
            <div class="form-group">
                <label for="directionSelect" class="mb-2"><?= Loc::getMessage('CURRENCY_SORT_DIRECTION') ?></label>
                <select class="form-control" id="currency-sort-direction">
                    <option value="ASC"><?= Loc::getMessage('CURRENCY_ASC') ?></option>
                    <option value="DESC"><?= Loc::getMessage('CURRENCY_DESC') ?></option>
                </select>
            </div>
        </div>
        <div id="currency-main">
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
                    <div class="pagination justify-content-center">
                        <?php foreach ($arResult["pages"]["nav"] as $pageNum): ?>
                            <button class="page-link <?= $arResult["pages"]["active"] == $pageNum ? 'active' : '' ?>"
                                    data-page="<?= $pageNum ?>">
                                <?= $pageNum ?>
                            </button>
                        <?php endforeach ?>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </div>

<?php endif; ?>
