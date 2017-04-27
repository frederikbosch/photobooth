const totalNumberOfPictures = 1;
let remoteTakePhoto = () => fetch('http://10.5.5.9/bacpac/SH?t=17171410&p=%01');
let remoteGetPhoto = () => fetch('http://10.5.5.9/bacpac/SH?t=17171410&p=%01');

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

function placePhotoOnScreen (photoNumber) {
  let photoContainer = document.getElementById('photo' + photoNumber);

  return new Promise(async function (resolve, reject) {
    remoteGetPhoto().then((src) => {
      let photo = document.createElement('img');
      photo.src = src;
      photoContainer.appendChild(photo);
      resolve();
    }, reject);
  });
}

async function start() {
  let commentContainer = document.getElementById('comment');
  commentContainer.textContent = "Get ready....";
  await wait(2000);
  commentContainer.textContent = "Here we go!";
  await wait(1000);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(2000);
  await placePhotoOnScreen(1);

  commentContainer.textContent = "One down, three to go!";
  await wait(3000);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(2000);
  await placePhotoOnScreen(2);

  commentContainer.textContent = "And one more!";
  await wait(3000);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(2000);
  await placePhotoOnScreen(3);

  commentContainer.textContent = "Final one!";
  await wait(3000);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(2000);
  await placePhotoOnScreen(4);

  commentContainer.textContent = "Photos are being printed!";
}

document.addEventListener("DOMContentLoaded", function (event) {
  document.addEventListener('keyup', function (e) {
    if (e.keyCode === 32) {
      start();
    }
  });
});