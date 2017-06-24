const totalNumberOfPictures = 1;
let busy = false;

let remoteTakePhoto = () => fetch("/api/take/", { method: "POST"});
let remoteGetPhoto = () => {
  return new Promise((resolve) => {
    fetch("/api/fetch/", { method: "POST"}).then(
      async (response) => {
        let json = await response.json();
        resolve(json['fileName']);
      }
    );
  });
};

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
  let data = new FormData();
  data.append('photo1', files[0]);
  data.append('photo2', files[1]);
  data.append('photo3', files[2]);
  data.append('photo4', files[3]);

  return fetch("/api/print/", { method: "POST", body: data});
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

  return new Promise(async function (resolve) {
    let photo = document.createElement('img');
    photo.src = '/api/photo/?photo=' + src;
    photoContainer.appendChild(photo);
    photo.addEventListener('load', () => resolve());
  });
}

function resetTakenPhotos () {
  let images = document.querySelectorAll('.results img');
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
  await wait(500);
  commentContainer.textContent = "Here we go!";
  await wait(500);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(500);
  src.push(await remoteGetPhoto());
  await placePhotoOnScreen(1, src[src.length - 1]);
  commentContainer.textContent = "One down, three to go!";
  await wait(500);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(500);
  src.push(await remoteGetPhoto());
  await placePhotoOnScreen(2, src[src.length - 1]);

  commentContainer.textContent = "And one more!";
  await wait(500);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(500);
  src.push(await remoteGetPhoto());
  await placePhotoOnScreen(3, src[src.length - 1]);

  commentContainer.textContent = "Final one!";
  await wait(500);
  commentContainer.textContent = "";
  await takePhoto();
  await wait(500);
  src.push(await remoteGetPhoto());
  await placePhotoOnScreen(4, src[src.length - 1]);

  commentContainer.textContent = "Photos are being printed!";
  await print(src);
  busy = false;
}

document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener('keyup', function (e) {
    if (e.keyCode === 32) {
      start();
    }
  });
});