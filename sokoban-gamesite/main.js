let canvas = document.querySelector("canvas");
let ctx = canvas.getContext("2d");

const WALK = 0;
const WALL = 1;
const PLAYER = 2;
const PLAYER_VOID = 6; // If the player is on a placement
const PLACEMENT = 3;
const BOX = 4;
const SUCCESS_BOX = 5;
let playerPosition = { x: 5, y: 6 };
let boxPositions = [
  { x: 7, y: 2 },
  { x: 2, y: 3 },
  { x: 5, y: 4 },
  { x: 5, y: 5 },
  { x: 6, y: 6 },
];

let arr = [
  [1, 1, 1, 1, 1, 1, 1, 1, 1, 1], // 10 elements
  [1, 1, 1, 1, 0, 3, 1, 1, 3, 1],
  [1, 0, 0, 0, 0, 0, 0, BOX, 0, 1],
  [1, 1, BOX, 0, 1, 0, 0, 0, 0, 1],
  [1, 3, 0, 0, 1, BOX, 0, 1, 1, 1],
  [1, 0, 0, 0, 3, BOX, PLAYER, 0, 0, 1],
  [1, 1, 0, 0, 0, 0, BOX, 0, 3, 1],
  [1, 1, 1, 0, 0, 0, 0, 1, 1, 1],
  [1, 1, 1, 1, 0, 0, 1, 1, 1, 1],
  [1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
];

console.log(arr);

let bricks = new Image();
bricks.src = "img/brickwall.png";

let background = new Image();
background.src = "img/floortile.png";

let player1 = new Image();
player1.src = "img/player.png";

let position = new Image();
position.src = "img/placement.svg";

let boximg = new Image();
boximg.src = "img/box.png";

function drawMaze() {
  for (let x = 0; x < arr.length; x++) {
    for (let y = 0; y < arr[x].length; y++) {
      console.log(arr[x][y]);

      if (arr[x][y] == WALL) {
        ctx.drawImage(bricks, x * 50, y * 50, 50, 50);
      } else if (arr[x][y] == WALK) {
        ctx.drawImage(background, x * 50, y * 50, 50, 50);
      } else if (arr[x][y] == PLACEMENT) {
        ctx.drawImage(position, x * 50, y * 50, 50, 50);
      } else if (arr[x][y] == BOX || arr[x][y] == SUCCESS_BOX) {
        ctx.drawImage(boximg, x * 50, y * 50, 50, 50);
      } else if (arr[x][y] == PLAYER || arr[x][y] == PLAYER_VOID) {
        playerPosition.y = y;
        playerPosition.x = x;
        console.log(playerPosition);
        ctx.drawImage(background, x * 50, y * 50, 50, 50);
        ctx.drawImage(player1, x * 50, y * 50, 50, 50);
      }
    }
  }
}

function boxCheck(targetTile) {
  if (targetTile === BOX || targetTile === PLACEMENT) {
    return true;
  } else {
    return false;
  }
}

function isWinner() {
  for (let i = 0; i < arr.length; i++) {
    for (let j = 0; j < arr[0].length; j++) {
      if (arr[i][j] === BOX) {
        return false;
      }
    }
  }

  return true;
}


let score = 0;
let savedTile = -1;
let hasWon = false;

document.addEventListener("keyup", function (event) {
  console.log(event.keyCode);
  /*
    left: 37
    up: 38
    right: 39
    down: 40
    */

  if (hasWon) {
    return;
  }

  let targetTileCoords = { x: 0, y: 0 };
  let afterTargetTileCoords = { x: 0, y: 0 };
  // Either a PLAYER or PLAYER_VOID;
  let playerTile = arr[playerPosition.x][playerPosition.y];

  let targetTile;
  let afterTargetTile;

  /**
   * HOW SHOULD THE LOGIC WORK?
   *
   * When a user moves, we have 4 cases:
   *    1. There is a "WALK"
   *        - When there is a walk, we just walk towards that spot
   *    2. There is a "WALL"
   *        - When there is a wall, we restrict the user from moving towards it.
   *    3. There is a "PLACEMENT"
   *        - When there is a placement, the user should be able to walk on it.
   *    4. There is a "BOX"
   *        - When there is a box, the following should occur:
   *            - We should check if there is a wall after it, if there is, do not allow user to move it.
   *            - Otherwise, allow the user to move it.
   */

  switch (event.keyCode) {
    case 37:
      targetTileCoords = { x: playerPosition.x - 1, y: playerPosition.y };
      afterTargetTileCoords = { x: playerPosition.x - 2, y: playerPosition.y };
      let step = new Audio("sound-effects/step2.mp3")
      step.play();
      break;
    case 38:
      targetTileCoords = { x: playerPosition.x, y: playerPosition.y - 1 };
      afterTargetTileCoords = { x: playerPosition.x, y: playerPosition.y - 2 };
      let step2 = new Audio("sound-effects/step.mp3")
      step2.play();
      break;
    // right
    case 39:
      targetTileCoords = { x: playerPosition.x + 1, y: playerPosition.y };
      afterTargetTileCoords = { x: playerPosition.x + 2, y: playerPosition.y };
      let step3 = new Audio("sound-effects/step2.mp3")
      step3.play();
      break;
    // down
    case 40:
      targetTileCoords = { x: playerPosition.x, y: playerPosition.y + 1 };
      afterTargetTileCoords = { x: playerPosition.x, y: playerPosition.y + 2 };
      let step4 = new Audio("sound-effects/step.mp3")
      step4.play();
      break;
  }

  targetTile = arr[targetTileCoords.x][targetTileCoords.y];
  afterTargetTile = arr[afterTargetTileCoords.x][afterTargetTileCoords.y];

  if (targetTile === WALK) {
    arr[targetTileCoords.x][targetTileCoords.y] = PLAYER;

    // If the player was a PLAYER, we draw a WALL under when they move
    if (playerTile === PLAYER) {
      arr[playerPosition.x][playerPosition.y] = WALK;
    } else {
      // If the player was a PLAYER_VOID, we draw a placement under
      arr[playerPosition.x][playerPosition.y] = PLACEMENT;
    }
  } else if (targetTile === PLACEMENT) {
    arr[targetTileCoords.x][targetTileCoords.y] = PLAYER_VOID;

    // If the player was a PLAYER, we draw a WALL under when they move
    if (playerTile === PLAYER) {
      arr[playerPosition.x][playerPosition.y] = WALK;
    } else {
      // If the player was a PLAYER_VOID, we draw a placement under
      arr[playerPosition.x][playerPosition.y] = PLACEMENT;
    }
  } else if (targetTile === WALL) {
    // Do nothing
  } else if (targetTile === BOX) {
    //  *        - When there is a box, the following should occur:
    //  *            - We should check if there is a wall after it, if there is, do not allow user to move it.
    //  *            - Otherwise, allow the user to move it.
    if (
      afterTargetTile === WALL ||
      afterTargetTile === BOX ||
      afterTargetTile === SUCCESS_BOX
    ) {
      // nothing
    } else {
      // If the afterTargetTile is a "PLACEMENT" then we must somehow save this information and redraw later
      arr[targetTileCoords.x][targetTileCoords.y] = PLAYER;
      if (afterTargetTile === PLACEMENT) {
        // put a SUCCESS_BOX
        arr[afterTargetTileCoords.x][afterTargetTileCoords.y] = SUCCESS_BOX;
      } else {
        arr[afterTargetTileCoords.x][afterTargetTileCoords.y] = BOX;
      }

      if (playerTile === PLAYER) {
        arr[playerPosition.x][playerPosition.y] = WALK;
      } else {
        // If the player was a PLAYER_VOID, we draw a placement under
        arr[playerPosition.x][playerPosition.y] = PLACEMENT;
      }
    }
  } else if (targetTile === SUCCESS_BOX) {
    if (
      afterTargetTile === WALL ||
      afterTargetTile === BOX ||
      afterTargetTile === SUCCESS_BOX
    ) {
      // nothing
    } else {
      // If the afterTargetTile is a "PLACEMENT" then we must somehow save this information and redraw later
      arr[targetTileCoords.x][targetTileCoords.y] = PLAYER_VOID;
      if (afterTargetTile === PLACEMENT) {
        // put a SUCCESS_BOX
        arr[afterTargetTileCoords.x][afterTargetTileCoords.y] = SUCCESS_BOX;
      } else {
        arr[afterTargetTileCoords.x][afterTargetTileCoords.y] = BOX;
      }

      if (playerTile === PLAYER) {
        arr[playerPosition.x][playerPosition.y] = WALK;
      } else {
        // If the player was a PLAYER_VOID, we draw a placement under
        arr[playerPosition.x][playerPosition.y] = PLACEMENT;
      }
    }
  }

  drawMaze();

  if (isWinner()) {
    hasWon = true;

    const overlay = document.querySelector("#dimmer-div");
    overlay.style.display = "flex";
    let win = new Audio("sound-effects/win.mp3")
    win.play();

    console.log("You have won!");
    //alert("You have won the game!");
  }


  
});

document.addEventListener("keyup", function (event) {
  switch (event.keyCode) {
    case 32:
      window.location.reload(true);
      break;

    default:
      break;
  }
});

window.addEventListener("load", drawMaze);
