<div id="accessibility_drawer_overlay"  class="drawer-overlay" onclick="closeDrawer('accessibility')"></div>

<div id="accessibility_wrap" class="accessibility-wrap side-drawer-wrap left-side-drawer">
    <a href="javascript:void(0)" class="closebtn" onclick="closeDrawer('accessibility')">&times;</a>
    <div class="accessibility side-drawer">
    <div class="acc-nav-wrap">
    
            <a target="_BLANK" href="<?= inner_url("negishut/") ?>" title="הצהרת נגישות">
            הצהרת נגישות
            </a>
            <br/>
            
            <hr/><br/>
            <a class="acc-nav-door" href="javascript://" onclick="toggleAccNav()">
                <span class="open-label">
                    לחץ לנווט מהיר
                </span>
                <span class="close-label hidden">
                    לחץ לסגירת נווט מהיר  
                </span>
            </a>
            <ul class="hidden acc-nav">          
                <li class="acc-nav-item">
                    <a href="#page_wrap" title="עבור לראש הדף" onclick="afterAccAnchorClick(true)">
                    עבור לראש הדף
                    </a>

                </li>
                <li class="acc-nav-item">
                    <a href="#footer" title="עבור לתחתית הדף" onclick="afterAccAnchorClick(false)">
                        עבור לתחתית הדף
                    </a>

                </li>             
            </ul>
        </div>
        <hr/><br/>
        <h3>תפריט נגישות</h3>

        <ul class="accessibility-menu">

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

  <div class="back-to-top-button-wrap hidden">
    <a class="back-to-top-button" href="javascript://" onclick="scroll_back_to_top()" > 
        <i class="fa fa-person-arrow-up-from-line"></i>
    </a>
  </div>