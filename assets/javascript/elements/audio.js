$(document).ready(function(){
    const playerButton = document.querySelector('.btn-play'),
          audio = document.querySelector('audio'),
          timeline = document.querySelector('.timeline'),
          timeCurrent = document.querySelector('.time-current'),
          timeRemain = document.querySelector('.time-remain');

    function toggleAudio () {
      if (audio.paused) {
        audio.play();
        playerButton.classList.add("playing");
      } else {
        audio.pause();
        playerButton.classList.remove("playing");
      }
    }

    function showMetaData () {
        const duration = getTime(audio.duration);
        timeRemain.innerHTML = duration;
    }

    function changeTimelinePosition () {
        const secondsRemain = audio.duration-audio.currentTime;

        if(secondsRemain == 0 || isNaN(secondsRemain) ) {
            timeRemain.innerHTML = '';
            timeline.style.backgroundSize = '0% 100%';
            timeline.value = 0;
        } else {
            const percentagePosition = (100*audio.currentTime) / audio.duration;
            timeline.style.backgroundSize = `${percentagePosition}% 100%`;
            timeline.value = percentagePosition;
            const currentTime = audio.currentTime;
            timeCurrent.innerHTML = getTime(currentTime);

            timeRemain.innerHTML = '-'+getTime(secondsRemain);
        }
    }

    function getTime(time) {
        const minutes = Math.floor(time / 60);
        const seconds = Math.floor(time - minutes * 60);
        const finalTime = str_pad_left(minutes, '0', 2) + ':' + str_pad_left(seconds, '0', 2);
        return finalTime;
    }
    function str_pad_left(string, pad, length) {
      return (new Array(length + 1).join(pad) + string).slice(-length);
    }

    audio.load();
    playerButton.addEventListener('click', toggleAudio);
    audio.addEventListener("loadeddata", showMetaData);

    audio.ontimeupdate = changeTimelinePosition;

    

    function audioEnded () {
        playerButton.classList.remove("playing");
        audio.pause();
        audio.currentTime = 0;
    }

    audio.onended = audioEnded;

    function changeSeek () {
      const time = (timeline.value * audio.duration) / 100;
      audio.currentTime = time;
    }

    timeline.addEventListener('change', changeSeek);
});