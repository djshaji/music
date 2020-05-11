function baseName(str)
{
   var base = new String(str).substring(str.lastIndexOf('/') + 1); 
    // if(base.lastIndexOf(".") != -1)       
    //     base = base.substring(0, base.lastIndexOf("."));
   return base;
}

function play_track (track, id = null, append = false) {
    // document.getElementById ("winamp_show").style.visibility = "visible"
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
    move_winamp ()
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

function move_winamp () {
    y = window.pageYOffset + ((window.innerHeight - 348) / 2)
    x = window.pageXOffset

    if (window.pageXOffset == 0) {
        x = x + (window.innerWidth / 2) - 137
    }

    winamp.store.dispatch({
        type: "UPDATE_WINDOW_POSITIONS",
        positions: {
          main: { x: x, y: y },
          playlist: { x: x, y: y + 232 },
          equalizer: { x: x, y: y + 116 }
        }
      })
}

function play_file () {
    arg = escape (location.href.split ('?')[1].toLowerCase ().replace ('+', ' '))
    a = document.getElementsByTagName ('a')
    songs = []
    id = null ;
    for (f of a) {
        if (f.hasAttribute ('download') && f.href.toLowerCase (). search (arg) != -1) {
            f.parentElement.parentElement.parentElement.parentElement.parentElement.classList.remove ("collapse")
            f.parentElement.parentElement.parentElement.classList.add ("active")
            f.parentElement.parentElement.parentElement.firstElementChild.classList.add ("text-white")

            f.scrollIntoView ()
            window.scrollBy (0, -100)
            songs.push ({url: f.href})
            id = f.parentElement.lastElementChild.id
        }
            
    }

    if (songs.length != 0) {
        document.getElementById ("play-play").onclick = function () {
            play_track (songs, id)
        }

        document.getElementById ("play-body").innerHTML = "<h2><i class='fa fa-play'></i>&nbsp;&nbsp;&nbsp" + unescape (arg) + "</h2>"
        document.getElementById ("page-title").innerText = unescape (arg) + ': Shaji Music'
        $('#play').modal("show")

    }
}

function footer_hooks () {
    play_file ()
}