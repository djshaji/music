function baseName(str)
{
   var base = new String(str).substring(str.lastIndexOf('/') + 1); 
    // if(base.lastIndexOf(".") != -1)       
    //     base = base.substring(0, base.lastIndexOf("."));
   return base;
}

function play_track (track, id = null, append = false) {
    document.getElementById ("winamp_show").style.visibility = "visible"
    winamp.func = winamp.setTracksToPlay
    if (append == true) {
        winamp.func = winamp.appendTracks
        console.log ("Appending tracks")
    }
    winamp.reopen ()
    if (typeof (track) == 'string') {
        winamp.func([
            {url: track}
        ]);
        id = baseName (track.replace (/ /g, '_'))
        winamp.renderWhenReady (document.getElementById (id))
    
    }
    else {
        winamp.func (track)
        winamp.renderWhenReady (document.getElementById (id))

    }

    console.log ("winamp rendered to", id)
}

function docReady(fn) {
    // see if DOM is already available
    if (document.readyState === "complete" || document.readyState === "interactive") {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

function scrollto_winamp () {
    document.getElementById ("main-window").scrollIntoView ()
    return 
    transform = document.getElementById ('main-window').parentElement.style.transform
    x = y = 0
    c = transform.split ("translate(") [1].split ('px')
    x = c [0]
    y = c [1].split (', ') [1]
    window.scrollTo (x, y)
    console.log (x, y)
}