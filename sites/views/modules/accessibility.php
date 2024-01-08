<div id="accessibility_drawer_overlay"  class="drawer-overlay" onclick="closeDrawer('accessibility')"></div>

<div id="accessibility_wrap" class="accessibility-wrap side-drawer-wrap left-side-drawer">
    <a href="javascript:void(0)" class="closebtn" onclick="closeDrawer('accessibility')">&times;</a>
    <div class="accessibility side-drawer">
    <div class="acc-nav-wrap">
    
            <a target="_BLANK" href="<?= inner_url("negishut/") ?>" title="<?= __tr("Accessibility statement") ?>">
            <?= __tr("Accessibility statement") ?>
            </a>
            <br/>
            
            <hr/><br/>
            <a class="acc-nav-door" href="javascript://" onclick="toggleAccNav()">
                <span class="open-label">
                    <?= __tr("Click here to quiqk nav") ?>
                </span>
                <span class="close-label hidden">
                    <?= __tr("Click here to close Quick nav") ?>  
                </span>
            </a>
            <ul class="hidden acc-nav">          
                <li class="acc-nav-item">
                    <a href="#page_wrap" title="<?= __tr("Go to top") ?>" onclick="afterAccAnchorClick(true)">
                    <?= __tr("Go to top") ?>
                    </a>

                </li>
                <li class="acc-nav-item">
                    <a href="#footer" title="<?= __tr("Go to bottom") ?>" onclick="afterAccAnchorClick(false)">
                        <?= __tr("Go to bottom") ?>
                    </a>

                </li>             
            </ul>
        </div>
        <hr/><br/>
        <h3><?= __tr("Accessibility menu") ?></h3>

        <ul class="accessibility-menu">

            <li class="accessibility-item font-size-control item">
                <labe class="label"><?= __tr("Font size") ?></labe>
                <a class="size plus acc-icon" href="javascript://" onclick="biggerfont()">+</a>
                
                <a class="size minus acc-icon" href="javascript://" onclick="smallerfont()">-</a>
                <span class="biggerfont  biggerfont-0-label state"><?= __tr("Normal") ?></span>
                <span class="biggerfont  biggerfont-1-label state"><?= __tr("Medium") ?></span>
                <span class="biggerfont  biggerfont-2-label state"><?= __tr("Big") ?></span>
                <span class="biggerfont  biggerfont-3-label state"><?= __tr("Bigger") ?></span>
                <div class="clear"></div>
            </li>
            <li class="accessibility-item contrast-control item">
                <labe class="label"><?= __tr("Contrast") ?></labe>
                <a class="contrast-on acc-icon" href="javascript://" onclick="contrastOn()">+</a>
               
                <a class="contrast-on acc-icon" href="javascript://" onclick="contrastOff()">-</a>
                <span class="contrast  contrast-on-label state"><?= __tr("On") ?></span>
                <span class="contrast  contrast-off-label state"><?= __tr("Off") ?></span>
                <div class="clear"></div>
            </li>
            <li class="accessibility-item links-control item">
                <labe class="label"><?= __tr("Emphasize links") ?></labe>
                <a class="links-on acc-icon" href="javascript://" onclick="linksOn()">+</a>
                
                <a class="links-on acc-icon" href="javascript://" onclick="linksOff()">-</a>
                <span class="linkson  links-on-label state"><?= __tr("On") ?></span>
                <span class="linkson  links-off-label state"><?= __tr("Off") ?></span>
                <div class="clear"></div>
            </li>
            <li class="accessibility-item reset-control item">
                <a class="reset acc-icon" href="javascript://" onclick="resetAcessability()"><?= __tr("Reset accessibility settings") ?></a>
                <div class="clear"></div>
            </li>
        </ul>
        
    </div>
  </div>

  <div class="back-to-top-button-wrap hidden">
    <a class="back-to-top-button" href="javascript://" onclick="scroll_back_to_top()" > 
        <i class="fa fa-person-arrow-up-from-line"></i>
    </a>
  </div>