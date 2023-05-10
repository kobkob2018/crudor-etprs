initNewsTicker = ()=>{
    const travelocity = 0.4
    document.querySelectorAll("ul#ticker01").forEach(strip=>{
        strip.dataset.animate_state = "play";
        let stripHeight = strip.offsetHeight;
        let mask = wrapElement(strip,"mask");
        let tickercontainer = wrapElement(mask,"tickercontainer");
        mask.addEventListener("mouseover",()=>{
            strip.dataset.animate_state = 'poused';
            //alert("yo");
           // console.log("innnn");
        });
        mask.addEventListener("mouseleave",()=>{
            strip.dataset.animate_state = 'play';
           // console.log("out now");
        });
        let containerHeight = tickercontainer.offsetHeight;
        let totalTravel = stripHeight;
        const tempo = totalTravel/travelocity;
        //console.log(totalTravel);

        function scrollnews(fromP){
          //  console.log("mid: "+fromP);
            animateStrip(fromP,(nextfromP)=>{scrollnews(nextfromP);});
           
        }

        function animateStrip(fromP,callbeck){
            if(strip.dataset.animate_state != "play"){
                strip.style.top = fromP + "px";
                setTimeout(()=>{callbeck(fromP);},1000);
                return;
            }
         //   console.log("rec:"+fromP);
            
            endP = strip.offsetTop - totalTravel;
            if(fromP < endP){
                fromP = containerHeight;
            }
            toP = fromP - 50;
          //  console.log("animateStrip");
            let spazioNext = strip.offsetTop - totalTravel;
            
            const newspaperSpinning = [
                { top: fromP+"px" },
                { top: toP + "px" },
            ];
              
            const newspaperTiming = {
                duration: tempo,
                iterations: 1,
            };

            fromP = toP;

            strip.animate(newspaperSpinning, newspaperTiming)
            .finished.then(function () {
            //    console.log("send:"+toP);
                callbeck(toP);
            });

        }
        let fromP = containerHeight;
        scrollnews(fromP);	
    });
}


wrapElement = (el, className)=>{
    const wrapper = document.createElement('div');
    wrapper.classList.add(className);
    el.parentNode.insertBefore(wrapper, el);
    wrapper.appendChild(el);
    return wrapper;
}

initNewsTicker();

