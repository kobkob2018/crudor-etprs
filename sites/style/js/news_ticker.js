initNewsTicker = ()=>{
    document.querySelectorAll(".news-ticker").forEach(strip=>{
        strip.dataset.animate_state = "play";
        let stripHeight = strip.offsetHeight;
        let mask = wrapElement(strip,"mask");
        let tickercontainer = wrapElement(mask,"tickercontainer");
        mask.addEventListener("mouseover",()=>{
            strip.dataset.animate_state = 'poused';
        });
        mask.addEventListener("mouseleave",()=>{
            strip.dataset.animate_state = 'play';
        });
        let containerHeight = tickercontainer.offsetHeight;
        let totalTravel = stripHeight;
        const tempo = 1700;

        function scrollnews(fromP){
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

