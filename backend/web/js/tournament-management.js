let currentMatch = null;
let mapWins = {team1: 0, team2: 0};
let selectedMaps = [];

function openMatchModal(matchElement) {
    currentMatch = matchElement;
    const team1Name = matchElement.dataset.team1Name;
    const team2Name = matchElement.dataset.team2Name;

    // Set team names
    document.getElementById('team1Name').textContent = team1Name;
    document.getElementById('team2Name').textContent = team2Name;

    // Reset scores and buttons
    mapWins = {team1: 0, team2: 0};
    selectedMaps = [];
    updateScores();
    document.querySelectorAll('.map-btn').forEach(btn => btn.classList.remove('active'));

    // Show modal
    $('#matchModal').modal('show');
}

// Map button clicks
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('map-btn')) {
        const team = e.target.dataset.team;
        const map = e.target.dataset.map;
        const mapKey = `team${team}_map${map}`;

        // Toggle map selection
        if (selectedMaps.includes(mapKey)) {
            // Deselect
            selectedMaps = selectedMaps.filter(m => m !== mapKey);
            e.target.classList.remove('active');
            mapWins[`team${team}`]--;
        } else {
            // Check if this map is already selected by other team
            const otherTeam = team === '1' ? '2' : '1';
            const otherMapKey = `team${otherTeam}_map${map}`;

            if (selectedMaps.includes(otherMapKey)) {
                alert('Este mapa já foi atribuído à outra equipa!');
                return;
            }

            // Check if team already has 2 maps (BO3 limit)
            if (mapWins[`team${team}`] >= 2) {
                alert('Esta equipa já venceu 2 mapas (máximo para BO3)!');
                return;
            }

            // Select
            selectedMaps.push(mapKey);
            e.target.classList.add('active');
            mapWins[`team${team}`]++;
        }

        updateScores();
    }
});

function updateScores() {
    document.getElementById('team1Score').textContent = mapWins.team1;
    document.getElementById('team2Score').textContent = mapWins.team2;
}

// Confirm match result
function confirmMatchResult() {
    if (mapWins.team1 === 0 && mapWins.team2 === 0) {
        alert('Selecione pelo menos um mapa vencedor!');
        return;
    }

    // Determine winner (needs 2 maps to win in BO3)
    let winner = null;
    if (mapWins.team1 >= 2) winner = 1;
    else if (mapWins.team2 >= 2) winner = 2;

    if (winner === null) {
        alert('Um dos times precisa vencer 2 mapas para ser declarado vencedor (BO3)!');
        return;
    }

    // Update match display
    const teams = currentMatch.querySelectorAll('.match-team');
    const scores = currentMatch.querySelectorAll('.team-score');

    scores[0].textContent = mapWins.team1;
    scores[1].textContent = mapWins.team2;

    teams.forEach(t => t.classList.remove('winner'));
    if (winner === 1) {
        teams[0].classList.add('winner');
    } else {
        teams[1].classList.add('winner');
    }

    // Update next round if exists
    const currentRound = parseInt(currentMatch.dataset.round);
    const nextRound = currentRound + 1;
    const matchIndex = parseInt(currentMatch.dataset.match);

    const nextRoundMatches = document.querySelectorAll(`.bracket-match[data-round="${nextRound}"]`);
    const nextMatchIndex = Math.floor(matchIndex / 2);

    if (nextRoundMatches[nextMatchIndex]) {
        const nextMatch = nextRoundMatches[nextMatchIndex];
        const nextTeams = nextMatch.querySelectorAll('.match-team');
        const positionInNextMatch = matchIndex % 2;

        const winnerName = winner === 1 ? currentMatch.dataset.team1Name : currentMatch.dataset.team2Name;
        const winnerId = winner === 1 ? currentMatch.dataset.team1Id : currentMatch.dataset.team2Id;

        nextTeams[positionInNextMatch].querySelector('.team-name').textContent = winnerName;
        nextTeams[positionInNextMatch].querySelector('.team-score').textContent = '0';
        nextMatch.setAttribute(`data-team${positionInNextMatch + 1}-id`, winnerId);
        nextMatch.setAttribute(`data-team${positionInNextMatch + 1}-name`, winnerName);
    }

    // Store partida_id in current match element for future saves
    const partidaId = currentMatch.dataset.partidaId;
    if (partidaId) {
        currentMatch.setAttribute('data-partida-id', partidaId);
    }

    $('#matchModal').modal('hide');
}

// Open stats modal
function openStatsModal() {
    if (!currentMatch) {
        alert('Nenhuma partida selecionada!');
        return;
    }

    const team1Id = currentMatch.dataset.team1Id;
    const team2Id = currentMatch.dataset.team2Id;
    const team1Name = currentMatch.dataset.team1Name;
    const team2Name = currentMatch.dataset.team2Name;

    console.log('Opening stats modal for teams:', {
        team1Id, team2Id, team1Name, team2Name
    });

    // Check if team IDs are valid
    if (!team1Id || !team2Id || team1Id === 'null' || team2Id === 'null') {
        alert('As equipas desta partida ainda não foram definidas. Por favor, complete a partida anterior primeiro.');
        return;
    }

    // Set team names in stats modal
    document.getElementById('statsTeam1Name').textContent = team1Name;
    document.getElementById('statsTeam2Name').textContent = team2Name;

    // Fetch team players
    fetchTeamPlayers(team1Id, team2Id);

    // Show stats modal
    $('#statsModal').modal('show');
}

// Fetch team players
function fetchTeamPlayers(team1Id, team2Id) {
    // Build the URL based on the current page URL structure
    const currentUrl = window.location.href;
    const path = window.location.pathname;

    let url;

    // Check if we're using query parameter routing (index.php?r=...)
    if (currentUrl.includes('index.php?r=')) {
        // Extract the base path up to index.php
        const basePath = path.substring(0, path.lastIndexOf('/') + 1) + 'index.php';
        url = `${window.location.origin}${basePath}?r=referee-dashboard/get-team-players&team1=${team1Id}&team2=${team2Id}`;
    } else if (path.includes('/management/')) {
        // Path-based routing
        const baseUrl = path.replace(/\/management\/\d+.*$/, '');
        url = `${window.location.origin}${baseUrl}/get-team-players?team1=${team1Id}&team2=${team2Id}`;
    } else {
        // Fallback
        url = `${window.location.origin}${path}/../get-team-players?team1=${team1Id}&team2=${team2Id}`;
    }

    console.log('Fetching players from:', url);

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.text(); // Get as text first to see what we're receiving
    })
    .then(text => {
        console.log('Raw response text:', text);

        try {
            const data = JSON.parse(text);
            console.log('Parsed response data:', data);

            if (data.success) {
                renderPlayerStats(data.team1Players, 'team1PlayersStats');
                renderPlayerStats(data.team2Players, 'team2PlayersStats');
            } else {
                alert('Erro ao carregar jogadores: ' + (data.message || 'Erro desconhecido'));
            }
        } catch (e) {
            console.error('JSON parse error:', e);
            alert('Erro ao processar resposta do servidor: ' + e.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Erro ao carregar jogadores: ' + error.message);
    });
}

// Render player stats inputs
function renderPlayerStats(players, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';

    if (!players || players.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">Nenhum jogador encontrado</p>';
        return;
    }

    players.forEach(player => {
        const playerRow = document.createElement('div');
        playerRow.className = 'player-stats-row';
        playerRow.innerHTML = `
            <h6>${player.username}</h6>
            <div class="stats-inputs">
                <div class="stat-input-group">
                    <label>Kills</label>
                    <input type="number"
                           class="stat-kills"
                           data-player-id="${player.id}"
                           min="0"
                           value="0"
                           placeholder="0">
                </div>
                <div class="stat-input-group">
                    <label>Deaths</label>
                    <input type="number"
                           class="stat-deaths"
                           data-player-id="${player.id}"
                           min="0"
                           value="0"
                           placeholder="0">
                </div>
                <div class="stat-input-group">
                    <label>K/D</label>
                    <input type="text"
                           class="stat-kd"
                           data-player-id="${player.id}"
                           readonly
                           value="0.00">
                </div>
            </div>
        `;

        // Auto-calculate K/D
        const killsInput = playerRow.querySelector('.stat-kills');
        const deathsInput = playerRow.querySelector('.stat-deaths');
        const kdInput = playerRow.querySelector('.stat-kd');

        const updateKD = () => {
            const kills = parseInt(killsInput.value) || 0;
            const deaths = parseInt(deathsInput.value) || 0;
            const kd = deaths > 0 ? (kills / deaths).toFixed(2) : kills.toFixed(2);
            kdInput.value = kd;
        };

        killsInput.addEventListener('input', updateKD);
        deathsInput.addEventListener('input', updateKD);

        container.appendChild(playerRow);
    });
}

// Save player stats
function savePlayerStats() {
    const statsData = [];
    const statRows = document.querySelectorAll('.player-stats-row');

    statRows.forEach(row => {
        const playerId = row.querySelector('.stat-kills').dataset.playerId;
        const kills = parseInt(row.querySelector('.stat-kills').value) || 0;
        const deaths = parseInt(row.querySelector('.stat-deaths').value) || 0;
        const kd = parseFloat(row.querySelector('.stat-kd').value) || 0;

        statsData.push({
            playerId: playerId,
            kills: kills,
            deaths: deaths,
            kd: kd
        });
    });

    // Send stats to server
    const currentUrl = window.location.href;
    const path = window.location.pathname;

    let url;

    // Check if we're using query parameter routing (index.php?r=...)
    if (currentUrl.includes('index.php?r=')) {
        // Extract the base path up to index.php
        const basePath = path.substring(0, path.lastIndexOf('/') + 1) + 'index.php';
        url = `${window.location.origin}${basePath}?r=referee-dashboard/save-stats`;
    } else if (path.includes('/management/')) {
        // Path-based routing
        const baseUrl = path.replace(/\/management\/\d+.*$/, '');
        url = `${window.location.origin}${baseUrl}/save-stats`;
    } else {
        // Fallback
        url = `${window.location.origin}${path}/../save-stats`;
    }

    console.log('Saving stats to:', url);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            stats: statsData,
            tournament_id: window.tournamentId,
            game_id: window.gameId
        })
    })
    .then(response => {
        console.log('Save stats response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Save stats response data:', data);
        if (data.success) {
            alert('Estatísticas guardadas com sucesso!');
            $('#statsModal').modal('hide');
        } else {
            alert('Erro ao guardar estatísticas: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao guardar estatísticas.');
    });
}

// Save all brackets
function saveBrackets() {
    const bracketData = [];
    const matches = document.querySelectorAll('.bracket-match');

    matches.forEach(match => {
        const round = match.dataset.round;
        const matchIndex = match.dataset.match;
        const matchId = match.dataset.matchId;
        const partidaId = match.dataset.partidaId;
        const teams = match.querySelectorAll('.match-team');
        const scores = match.querySelectorAll('.team-score');

        bracketData.push({
            round: parseInt(round),
            match: parseInt(matchIndex),
            match_id: matchId ? parseInt(matchId) : null,
            partida_id: partidaId ? parseInt(partidaId) : null,
            team1: {
                id: match.dataset.team1Id,
                name: match.dataset.team1Name,
                score: parseInt(scores[0].textContent) || 0
            },
            team2: {
                id: match.dataset.team2Id,
                name: match.dataset.team2Name,
                score: parseInt(scores[1].textContent) || 0
            },
            winner: teams[0].classList.contains('winner') ? 1 :
                    (teams[1].classList.contains('winner') ? 2 : null)
        });
    });

    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': window.csrfToken
        },
        body: JSON.stringify({
            brackets: bracketData,
            tournament_id: window.tournamentId
        })
    })
    .then(response => response.text())
    .then(text => {
        console.log('RAW response:', text);

        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            alert('Resposta não é JSON: ' + text);
            return;
        }

        if (data.success) {
            alert(data.message || 'Resultados guardados com sucesso!');
        } else {
            alert('Erro ao guardar resultados: ' + (data.message || 'Erro desconhecido'));
            console.log('Errors:', data.errors);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Erro ao guardar resultados: ' + error.message);
    });
}



// Initialize event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Confirm match result button
    const confirmMatchBtn = document.getElementById('confirmMatch');
    if (confirmMatchBtn) {
        confirmMatchBtn.addEventListener('click', confirmMatchResult);
    }

    // Open stats modal button
    const openStatsBtn = document.getElementById('openStatsModal');
    if (openStatsBtn) {
        openStatsBtn.addEventListener('click', openStatsModal);
    }

    // Save stats button
    const saveStatsBtn = document.getElementById('saveStats');
    if (saveStatsBtn) {
        saveStatsBtn.addEventListener('click', savePlayerStats);
    }

    // Save brackets button
    const saveBracketsBtn = document.getElementById('saveBrackets');
    if (saveBracketsBtn) {
        saveBracketsBtn.addEventListener('click', saveBrackets);
    }
});
