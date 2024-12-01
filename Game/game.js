let tiles = [];
let emptyTileIndex = 8; // Empty tile position (index 8 corresponds to bottom right corner)
let puzzleSize = 3; // 3x3 puzzle
let timerInterval;
let timeElapsed = 0;
let points = 0;
let lives = 3;
let level = 1;
let username = '';
let isGameActive = false;

// Game Initialization
function startGame() {
    fetchData()
    username = document.getElementById('username').value;
    

    // Set user name
    document.getElementById('player-name').textContent = username;

    // Hide start game button and show game info
    document.getElementById('user-info').style.display = 'none';
    document.getElementById('game-info').style.display = 'block';

    // Initialize game variables
    points = 0;
    lives = 3;
    level = 1;
    timeElapsed = 0;
    updateGameInfo();

    // Start the puzzle game
    tiles = [];
    for (let i = 0; i < puzzleSize * puzzleSize; i++) {
        if (i === emptyTileIndex) {
            tiles.push(null); // Empty space
        } else {
            tiles.push(i + 1); // Numbered tiles
        }
    }

    shuffleTiles();
    renderTiles();
    startTimer();

    isGameActive = true;
}

// Start Timer
function startTimer() {
    timerInterval = setInterval(() => {
        timeElapsed++;
        let minutes = Math.floor(timeElapsed / 60);
        let seconds = timeElapsed % 60;
        document.getElementById('timer').textContent = `${formatTime(minutes)}:${formatTime(seconds)}`;
    }, 1000);
}

// Format time for display (02:09, 10:05 etc.)
function formatTime(time) {
    return time < 10 ? `0${time}` : time;
}

// Render the puzzle grid
function renderTiles() {
    const puzzle = document.getElementById('puzzle');
    puzzle.innerHTML = '';
    tiles.forEach((tile, index) => {
        const tileElement = document.createElement('div');
        tileElement.classList.add('tile');
        if (tile !== null) {
            tileElement.innerText = tile;
            tileElement.setAttribute('data-index', index);
            tileElement.addEventListener('click', () => moveTile(index));
        }
        puzzle.appendChild(tileElement);
    });
}

// Shuffle the tiles
function shuffleTiles() {
    let shuffled = false;
    while (!shuffled) {
        for (let i = tiles.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [tiles[i], tiles[j]] = [tiles[j], tiles[i]]; // Swap elements
        }
        if (isSolvable()) {
            shuffled = true;
        }
    }
}


// Check if the puzzle is solvable
function isSolvable() {
    let inversionCount = 0;
    for (let i = 0; i < tiles.length; i++) {
        if (tiles[i] !== null) {
            for (let j = i + 1; j < tiles.length; j++) {
                if (tiles[j] !== null && tiles[i] > tiles[j]) {
                    inversionCount++;
                }
            }
        }
    }
    return inversionCount % 2 === 0;
}

// Move the tile into the empty space
function moveTile(index) {
    if (!isGameActive) return;

    const emptyIndex = tiles.indexOf(null);
    const validMoves = [
        emptyIndex - 1, emptyIndex + 1, emptyIndex - puzzleSize, emptyIndex + puzzleSize
    ];

    if (validMoves.includes(index)) {
        // Swap tiles
        [tiles[emptyIndex], tiles[index]] = [tiles[index], tiles[emptyIndex]];
        renderTiles();
        checkWin();
    }
}

// Check if the puzzle is solved
function checkWin() {
    const isSolved = tiles.every((tile, index) => tile === (index + 1) || (tile === null && index === tiles.length - 1));
    if (isSolved) {
        setTimeout(() => {
            points += level * 10;
            level++;
            alert(`Congratulations! Level ${level - 1} completed!`);
            if (lives > 0) {
                startGame();

            } else {
                alert("Game Over!");
                logout();
            }
        }, 100);
    }
}

// Update game info (Lives, Points, Level)
function updateGameInfo() {
    document.getElementById('lives').textContent = lives;
    document.getElementById('points').textContent = points;
    document.getElementById('level').textContent = level;
}

// Handle Log Out
function logout() {
    clearInterval(timerInterval);
    isGameActive = false;
    document.getElementById('game-info').style.display = 'none';
    document.getElementById('user-info').style.display = 'block';
    document.getElementById('username').value = '';
}


async function fetchData() {
    try {
        const response = await fetch('https://marcconrad.com/uob/banana/api.php', { method: 'GET' });
        if (response.ok) {
            const data = await response.json();
            const imageUrl = data['question'];
            const answer = data['solution'];
            console.log(imageUrl)
           
        } else {
            console.error(`Failed to retrieve data: ${response.status}`);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

