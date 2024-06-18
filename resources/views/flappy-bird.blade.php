<x-app-layout>
    <x-slot name="header">
        <header>
            <h1>Flappy Bird</h1>
            <div class="score-container">
                <div id="bestScore"></div>
                <div id="currentScore"></div>
                <div id="sceneState"></div>
            </div>
        </header>
    </x-slot>

    <div x-data="flappyBirdGame()">
        <canvas id="canvas" width="600" height="400"></canvas>
    </div>

    <div id="resultPopup" class="popup">
        <div class="popup-content">
            <span class="close" id="closePopup">&times;</span>
            <p>Your score : <span id="roundScore"></span></p>

            <div id="selectedReward" style="display: none">
                <label for="reward">Select your reward:</label>
                <select id="reward" name="reward">
                    @foreach($rewards as $key => $reward)
                        <option value="{{ $key }}">{{ $reward }}</option>
                    @endforeach
                </select>
                <button id="claimRewardBtn">Claim Reward</button>
            </div>

        </div>
    </div>

    <script>
        const apis = {
            start: '{{ route('game.play.start') }}',
            finish: '{{ route('game.play.finish') }}',
        };

        function apiService(url, options = {}) {
            // Default headers
            const defaultHeaders = {
                'Content-Type': 'application/json',
                'Authorization': `Bearer {{ session()->get('auth_token') }}`,
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            };

            // Merge default headers with options headers
            options.headers = { ...defaultHeaders, ...options.headers };

            console.log(options)

            // Perform fetch request
            return fetch(url, options);
        }

        function flappyBirdGame() {
            return {
                canvas: null,
                ctx: null,
                img: new Image(),
                gameState: 'ready', // ready, playing, gameOver
                gravity: 0.5,
                speed: 4.2,
                size: [51, 36],
                jump: -11.5,
                cTenth: null,
                index: 0,
                bestScore: 0,
                flight: null,
                flyHeight: null,
                currentScore: 0,
                pipes: [],
                pipeWidth: 78,
                pipeGap: 270,
                sceneState: 'scene1', // Initial scene state
                reward: null,
                rewardList: @js($rewards),
                gamePlayId: null,
                historyGameId: null,

                init() {
                    this.canvas = document.getElementById('canvas');
                    this.ctx = this.canvas.getContext('2d');
                    this.img.src = "https://i.ibb.co/Q9yv5Jk/flappy-bird-set.png";
                    this.cTenth = this.canvas.width / 10;

                    this.img.onload = () => this.render();

                    this.setup();
                },

                pipeLoc() {
                    return (Math.random() * ((this.canvas.height - (this.pipeGap + this.pipeWidth)) - this.pipeWidth)) + this.pipeWidth;
                },

                setup() {
                    this.currentScore = 0;
                    this.flight = this.jump;
                    this.flyHeight = (this.canvas.height / 2) - (this.size[1] / 2);
                    this.pipes = Array(3).fill().map((a, i) => [this.canvas.width + (i * (this.pipeGap + this.pipeWidth)), this.pipeLoc()]);
                    this.gameState = 'ready'; // Set game state to ready
                    this.sceneState = 'scene1'; // Set initial scene state
                },

                startGame() {
                    if (this.gameState === 'ready') {
                        this.gameState = 'playing';
                        this.flight = this.jump;
                        this.sceneState = 'scene1'; // Start with next round

                        apiService(apis.start, {
                            method: 'POST',
                            body: JSON.stringify({
                                gameId: 1,
                            })
                        }).then(async (response) => {
                            const res = await response.json();
                            if (!res.success) {
                                this.endGame();
                                return;
                            }

                            this.historyGameId = res.data.history_id;
                            this.gamePlayId = res.data.game_play_id;
                        });
                    }

                    if (this.gameState === 'playing') {
                        this.flight = this.jump;
                    }
                },

                render() {
                    this.index++;

                    // Clear the canvas
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

                    // Draw background first part
                    this.ctx.drawImage(this.img, 0, 0, 275, 228, -((this.index * (this.speed / 2)) % this.canvas.width) + this.canvas.width, 0, this.canvas.width, this.canvas.height);
                    // Draw background second part
                    this.ctx.drawImage(this.img, 0, 0, 275, 228, -(this.index * (this.speed / 2)) % this.canvas.width, 0, this.canvas.width, this.canvas.height);

                    if (this.gameState === 'playing') {
                        this.pipes.map(pipe => {
                            pipe[0] -= this.speed;

                            // Top pipe
                            this.ctx.drawImage(this.img, 432, 588 - pipe[1], this.pipeWidth, pipe[1], pipe[0], 0, this.pipeWidth, pipe[1]);
                            // Bottom pipe
                            this.ctx.drawImage(this.img, 432 + this.pipeWidth, 108, this.pipeWidth, this.canvas.height - pipe[1] + this.pipeGap, pipe[0], pipe[1] + this.pipeGap, this.pipeWidth, this.canvas.height - pipe[1] + this.pipeGap);

                            // Give 1 point & create new pipe
                            if (pipe[0] <= -this.pipeWidth) {
                                this.currentScore++;
                                this.bestScore = Math.max(this.bestScore, this.currentScore);
                                this.changeState(this.currentScore, this.reward);

                                this.pipes = [...this.pipes.slice(1), [this.pipes[this.pipes.length - 1][0] + this.pipeGap + this.pipeWidth, this.pipeLoc()]];
                            }

                            // Check collision with pipes
                            if ([
                                pipe[0] <= this.cTenth + this.size[0],
                                pipe[0] + this.pipeWidth >= this.cTenth,
                                pipe[1] > this.flyHeight || pipe[1] + this.pipeGap < this.flyHeight + this.size[1]
                            ].every(elem => elem)) {
                                this.endGame();
                            }
                        });

                        // Draw bird
                        this.ctx.drawImage(this.img, 432, Math.floor((this.index % 9) / 3) * this.size[1], ...this.size, this.cTenth, this.flyHeight, ...this.size);
                        this.flight += this.gravity;
                        this.flyHeight = Math.min(this.flyHeight + this.flight, this.canvas.height - this.size[1]);
                    } else {
                        // Draw bird in ready or game over state
                        this.ctx.drawImage(this.img, 432, Math.floor((this.index % 9) / 3) * this.size[1], ...this.size, ((this.canvas.width / 2) - this.size[0] / 2), this.flyHeight, ...this.size);
                        this.flyHeight = (this.canvas.height / 2) - (this.size[1] / 2);

                        // Draw best score and click to play text
                        this.ctx.fillText(`Best score: ${this.bestScore}`, 85, 245);
                        this.ctx.fillText('Click to play', 90, 535);
                        this.ctx.font = "bold 30px courier";
                    }

                    // Update score displays
                    document.getElementById('bestScore').innerHTML = `Best: ${this.bestScore}`;
                    document.getElementById('currentScore').innerHTML = `Current: ${this.currentScore}`;

                    // Continue rendering
                    window.requestAnimationFrame(() => this.render());
                },

                changeState(score, reward)  {
                    if (score <= 5) {
                        this.sceneState = 'scene1';
                        return;
                    }

                    if (reward === null) {
                        const rewards = document.getElementById('selectedReward');
                        rewards.style.display = 'block';
                        this.sceneState = 'scene2';
                        return;
                    }

                    this.sceneState = 'scene3';
                },

                displayPopup(score) {
                    const popup = document.getElementById('resultPopup');
                    const scoreElement = document.getElementById('roundScore');
                    scoreElement.textContent = score;
                    popup.style.display = 'block';
                },

                async closePopup() {
                    const popup = document.getElementById('resultPopup');
                    const rewards = document.getElementById('selectedReward');
                    popup.style.display = 'none';
                    rewards.style.display = 'none';

                    await this.finish();

                    this.setup();
                },

                endGame() {
                    this.gameState = 'gameOver';

                    switch (this.sceneState) {
                        case 'scene1':
                            console.log('End of round 1: Score only');
                            // Display score notification for Round 1
                            this.displayPopup(this.currentScore); // Implement function to display score
                            break;
                        case 'scene2':
                            console.log('End of round 2: Score + possibly win a reward');
                            // Implement logic for displaying score and reward notification for Round 2
                            this.displayPopup(this.currentScore);
                            break;
                        case 'scene3':
                            console.log('End of round 3: Score + don’t receive reward');
                            // Implement logic for displaying score notification for Round 3
                            // No reward notification for Round 3
                            // End game after Round 3
                            this.displayPopup(this.currentScore);
                            break;
                        default:
                            break;
                    }
                },

                async finish() {
                    let reward = null;

                    if (this.currentScore >= 5) {
                        reward = document.getElementById('reward').value;
                    }

                    return await apiService(apis.finish, {
                        method: 'POST',
                        body: JSON.stringify({
                            gameId: 1,
                            gamePlayId: this.gamePlayId,
                            historyId: this.historyGameId,
                            score: this.currentScore,
                            reward,
                            scene: this.sceneState
                        })
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const game = flappyBirdGame();
            game.init();

            // Start game on canvas click
            document.getElementById('canvas').addEventListener('click', () => {
                game.startGame();
            });

            document.getElementById('closePopup').addEventListener('click', async () => {
                await game.closePopup();
            });

            document.getElementById('claimRewardBtn').addEventListener('click', async () => {
                await game.closePopup();
            });
        });
    </script>
</x-app-layout>
