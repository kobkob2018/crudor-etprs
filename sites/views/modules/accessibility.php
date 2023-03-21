<div id="accessibility_drawer_overlay"  class="drawer-overlay" onclick="closeDrawer('accessibility')"></div>

<div id="accessibility_wrap" class="accessibility-wrap side-drawer-wrap left-side-drawer">
    <a href="javascript:void(0)" class="closebtn" onclick="closeDrawer('accessibility')">&times;</a>
    <div class="accessibility side-drawer">
        <h3>תפריט נגישות</h3>
        <ul class="accessibility-menu nav-ul">

            <li class="accessibility-item font-size-control item">
                <labe class="label">גודל פונט</labe>
                <a class="size plus acc-icon" href="javascript://" onclick="biggerfont()">+</a>
                
                <a class="size minus acc-icon" href="javascript://" onclick="smallerfont()">-</a>
                <span class="biggerfont  biggerfont-0-label state">רגיל</span>
                <span class="biggerfont  biggerfont-1-label state">בינוני</span>
                <span class="biggerfont  biggerfont-2-label state">גדול</span>
                <span class="biggerfont  biggerfont-3-label state">ענק</span>
                <div class="clear"></div>
            </li>
            <li class="accessibility-item contrast-control item">
                <labe class="label">ניגודיות</labe>
                <a class="contrast-on acc-icon" href="javascript://" onclick="contrastOn()">+</a>
               
                <a class="contrast-on acc-icon" href="javascript://" onclick="contrastOff()">-</a>
                <span class="contrast  contrast-on-label state">מופעל</span>
                <span class="contrast  contrast-off-label state">מופסק</span>
                <div class="clear"></div>
            </li>
            <li class="accessibility-item links-control item">
                <labe class="label">הדגש קישורים</labe>
                <a class="links-on acc-icon" href="javascript://" onclick="linksOn()">+</a>
                
                <a class="links-on acc-icon" href="javascript://" onclick="linksOff()">-</a>
                <span class="linkson  links-on-label state">מופעל</span>
                <span class="linkson  links-off-label state">מופסק</span>
                <div class="clear"></div>
            </li>
            <li class="accessibility-item reset-control item">
                <a class="reset acc-icon" href="javascript://" onclick="resetAcessability()">אפס הגדרות נגישות</a>
                <div class="clear"></div>
            </li>
        </ul>
        
    </div>
  </div>