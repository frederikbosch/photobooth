const totalNumberOfPictures = 1;
let busy = false;
let remoteTakePhoto = () => fetch('http://10.5.5.9/bacpac/SH?t=17171410&p=%01');
let remoteGetPhoto = () => {
  return new Promise((resolve) => {
    fetch('http://10.5.5.9:8080/videos/DCIM/100GOPRO/').then(
        async (response) => {
          let html = await response.text();
          
          let parser = new DOMParser();
          let dom = parser.parseFromString(html, "text/html");

          let link = dom.getElementsByTagName('a');
          let lastLink = link[link.length - 1];
          resolve('http://10.5.5.9:8080/videos/DCIM/100GOPRO/' + lastLink.getAttribute('href'));
        }
    );
  });
};

let urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('debug')) {
  remoteTakePhoto = () => Promise.resolve();
  remoteGetPhoto = () => Promise.resolve('test/picture.jpg');
}

function wait(ms) {
  return new Promise(r => setTimeout(r, ms));
}

async function showRandomFunnyPicture (ms) {
  return new Promise(async (resolve) => {
    let pictureContainer = document.getElementById('picture');
    let picture = document.createElement('img');
    picture.src = 'img/' + Math.ceil(Math.random() * totalNumberOfPictures) + '.jpg';
    pictureContainer.appendChild(picture);

    await wait(ms);
    pictureContainer.removeChild(picture);
    resolve();
  });
}

async function print(files) {
  var data = new FormData();
  data.append('photo1', files[0]);
  data.append('photo2', files[1]);
  data.append('photo3', files[2]);
  data.append('photo4', files[3]);

  return fetch(
    "/api/print/",
    {
        method: "POST",
        body: data
    }
  );
}

function takePhoto() {
  return new Promise(async function (resolve, reject) {
    let countdownContainer = document.getElementById('countdown');
    countdownContainer.textContent = "3";
    await wait(1000);
    countdownContainer.textContent = "2";
    await wait(1000);
    countdownContainer.textContent = "1";
    await wait(1000);
    countdownContainer.textContent = "cheese!";

    showRandomFunnyPicture(2000).then(() => {
      countdownContainer.textContent = "";
    });

    remoteTakePhoto().then(resolve, reject);
  });
}

function placePhotoOnScreen (photoNumber, src) {
  let photoContainer = document.getElementById('photo' + photoNumber);

  return new Promise(async function (resolve, reject) {
    let photo = document.createElement('img');
    photo.src = src;
    photoContainer.appendChild(photo);
    resolve();
  });
}

function resetTakenPhotos () {
  let images = document.getElementById('results').getElementsByTagName('img');
  while (images.length > 0) {
    images[0].parentNode.removeChild(images[0]);
  }
}

async function start() {
  if (busy) {
    return;
  }

  resetTakenPhotos();  

  busy = true;
  let src = [];
  let commentContainer = document.getElementById('comment');
  commentContainer.textContent = "Get ready....";
  await wait(2000);
  commentContainer.textContent = "Here we go!";
  await wait(1000);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(2000);
  src.push(await remoteGetPhoto());
  await placePhotoOnScreen(1, src[src.length - 1]);
  commentContainer.textContent = "One down, three to go!";
  await wait(3000);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(2000);
  src.push(await remoteGetPhoto());
  await placePhotoOnScreen(2, src[src.length - 1]);

  commentContainer.textContent = "And one more!";
  await wait(3000);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(2000);
  src.push(await remoteGetPhoto());
  await placePhotoOnScreen(3, src[src.length - 1]);

  commentContainer.textContent = "Final one!";
  await wait(3000);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(2000);
  src.push(await remoteGetPhoto());
  await placePhotoOnScreen(4, src[src.length - 1]);

  commentContainer.textContent = "Photos are being printed!";
  await print(src);
  busy = false;
}

document.addEventListener("DOMContentLoaded", function (event) {
  document.addEventListener('keyup', function (e) {
    if (e.keyCode === 32) {
      start();
    }
  });
});