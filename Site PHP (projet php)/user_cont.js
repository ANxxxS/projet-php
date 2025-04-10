document.addEventListener('DOMContentLoaded', function() {
    const boiteAmis = document.getElementById('boite_user');
    
    if (!document.getElementById("conteneur-boite-messages")) {
        const chatContainer = document.createElement("div");
        chatContainer.id = "conteneur-boite-messages";
        chatContainer.style.position = "fixed";
        chatContainer.style.bottom = "0";
        chatContainer.style.right = "285px";
        chatContainer.style.zIndex = "999";
        chatContainer.style.display = "flex";
        chatContainer.style.flexDirection = "column-reverse";
        chatContainer.style.alignItems = "flex-end";
        chatContainer.style.gap = "10px";
        document.body.appendChild(chatContainer);
    }

    let amiActuels = {}; 
    let maxBoitesAffichees = calculerMaxBoites(); 
    
    window.addEventListener('resize', function() {
        maxBoitesAffichees = calculerMaxBoites();
        reorganiserBoites();
    });
    
    function calculerMaxBoites() {
        const largeurEcran = window.innerWidth;
        if (largeurEcran < 576) return 1;
        if (largeurEcran < 992) return 2;
        return 3;
    }
    
    function chargerAmis() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'liste_amis.php', true);
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                const titre = boiteAmis.querySelector('h1');
                boiteAmis.innerHTML = '';
                if (titre) boiteAmis.appendChild(titre);
                
                const listeAmis = document.createElement('ul');
                listeAmis.className = 'liste-amis';
                listeAmis.style.cssText = `
                    list-style: none;
                    padding: 0;
                    margin: 0;
                `;
                
                try {
                    const amis = JSON.parse(xhr.responseText);
                    
                    if (amis.length > 0) {
                        amis.forEach(function(ami) {
                            const itemAmi = document.createElement('li');
                            itemAmi.className = 'ami-item';
                            itemAmi.dataset.amiId = ami.id;
                            itemAmi.dataset.amiNom = `${ami.nom} ${ami.prenom}`;
                            itemAmi.style.cssText = `
                                display: flex;
                                align-items: center;
                                padding: 10px;
                                cursor: pointer;
                                border-bottom: 1px solid #ddd;
                                transition: background-color 0.2s;
                            `;
                            
                            itemAmi.addEventListener('mouseenter', () => {
                                itemAmi.style.backgroundColor = '#f0f0f0';
                            });
                            
                            itemAmi.addEventListener('mouseleave', () => {
                                itemAmi.style.backgroundColor = 'transparent';
                            });
                            
                            itemAmi.addEventListener('click', function() {
                                if (!amiActuels[ami.id]) {
                                    ouvrirDiscussionAvecAmi(ami.id, `${ami.nom} ${ami.prenom}`);
                                } else {
                                    amiActuels[ami.id].boite.style.zIndex = "1001";
                                    setTimeout(() => { amiActuels[ami.id].boite.style.zIndex = "1000"; }, 300);
                                }
                            });
                            
                            const imgAmi = document.createElement('img');
                            imgAmi.src = ami.photo_name && ami.photo_name !== "noimage.jpg" 
                                ? `user_data/${ami.id}/${ami.photo_name}` 
                                : "images/noimage.jpg";
                            imgAmi.alt = "Photo de profil";
                            imgAmi.className = 'ami-photo';
                            imgAmi.style.cssText = `
                                width: 40px;
                                height: 40px;
                                border-radius: 50%;
                                margin-right: 10px;
                                object-fit: cover;
                            `;
                            
                            const nomAmi = document.createElement('span');
                            nomAmi.textContent = `${ami.nom} ${ami.prenom}`;
                            nomAmi.className = 'ami-nom';
                            
                            itemAmi.appendChild(imgAmi);
                            itemAmi.appendChild(nomAmi);
                            listeAmis.appendChild(itemAmi);
                        });
                    } else {
                        const messageVide = document.createElement('p');
                        messageVide.textContent = "Vous n'avez pas d'amis.";
                        messageVide.style.cssText = 'text-align: center; padding: 20px; color: #666;';
                        listeAmis.appendChild(messageVide);
                    }
                } catch (e) {
                    console.error("Erreur lors du traitement de la réponse:", e);
                    const messageErreur = document.createElement('p');
                    messageErreur.textContent = "Erreur lors du chargement des amis.";
                    messageErreur.style.cssText = 'text-align: center; padding: 20px; color: #f44336;';
                    listeAmis.appendChild(messageErreur);
                }
                
                boiteAmis.appendChild(listeAmis);
            } else {
                console.error("Erreur lors de la requête:", xhr.status);
            }
        };
        
        xhr.onerror = function() {
            console.error("Erreur réseau lors de la requête");
        };
        
        xhr.send();
    }
  
    
    
    function ouvrirDiscussionAvecAmi(amiId, nomAmi) {
        if (amiActuels[amiId]) return;

        const largeurEcran = window.innerWidth;
        const largeurBoite = largeurEcran < 576 ? Math.min(largeurEcran * 0.9, 300) : 280;

        if (Object.keys(amiActuels).length >= maxBoitesAffichees) {
            const amiIDLePlusAncien = Object.keys(amiActuels)[0];
            fermerDiscussionAvecAmi(amiIDLePlusAncien);
        }

        const boiteMessage = document.createElement("div");
        boiteMessage.classList.add("boite-message");
        boiteMessage.style.cssText = `
            width: ${largeurBoite}px;
            max-width: 90vw;
            background-color: #fff;
            border-radius: 8px 8px 0 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            z-index: 1000;
            transition: all 0.3s ease;
            margin-left: 10px;
            position: relative;
        `;

        if (!document.getElementById('chat-animations')) {
            const style = document.createElement('style');
            style.id = 'chat-animations';
            style.textContent = `
                @keyframes clignoter {
                    0% { box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); }
                    50% { box-shadow: 0 2px 15px rgba(59, 89, 152, 0.8); }
                    100% { box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); }
                }
            `;
            document.head.appendChild(style);
        }

        boiteMessage.innerHTML = `
            <div style="background-color: #3b5998; padding: 12px; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: white; font-weight: bold;">${nomAmi}</span>
                <div>
                    <button class="minimiser-discussion" data-ami-id="${amiId}" style="
                        background: none;
                        border: none;
                        color: white;
                        font-size: 16px;
                        cursor: pointer;
                        margin-right: 8px;
                    ">_</button>
                    <button class="fermer-discussion" data-ami-id="${amiId}" style="
                        background: none;
                        border: none;
                        color: white;
                        font-size: 16px;
                        cursor: pointer;
                    ">×</button>
                </div>
            </div>
            <div class="discussion" style="
                height: 250px; 
                overflow-y: auto; 
                background-color: #f5f5f5;
                padding: 10px;
                scroll-behavior: smooth;
            ">
                <div class="messages-loading" style="text-align: center; padding: 20px; color: #666;">
                    Chargement des messages...
                </div>
            </div>
            <div class="input-container" style="
                display: flex; 
                align-items: center; 
                padding: 10px; 
                border-top: 1px solid #eee;
                background-color: white;
            ">
                <input type="text" class="message-text" placeholder="Écrivez votre message..." style="
                    flex: 1; 
                    padding: 8px 12px; 
                    border: 1px solid #ddd; 
                    border-radius: 20px;
                    outline: none;
                ">
                <button class="btn-envoyer-message" data-ami-id="${amiId}" style="
                    background: #3b5998;
                    color: white;
                    border: none;
                    border-radius: 50%;
                    width: 32px;
                    height: 32px;
                    margin-left: 8px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <span style="transform: rotate(90deg);">🚀</span>
                </button>
            </div>
        `;

        document.getElementById("conteneur-boite-messages").appendChild(boiteMessage);

        boiteMessage.style.position = 'absolute';
        boiteMessage.style.bottom = '0';
        boiteMessage.style.right = '0';

        amiActuels[amiId] = {
            boite: boiteMessage,
            container: boiteMessage.querySelector('.discussion'),
            dernierMessageId: 0,
            minimise: false,
            dateOuverture: new Date(),
            intervalId: setInterval(() => {
                mettreAJourMessages(amiId, boiteMessage.querySelector('.discussion'));
            }, 3000)
        };

        reorganiserBoites();
        chargerMessages(amiId, boiteMessage.querySelector('.discussion'));

        boiteMessage.querySelector('.fermer-discussion').addEventListener('click', () => fermerDiscussionAvecAmi(amiId));
        
        boiteMessage.querySelector('.minimiser-discussion').addEventListener('click', function() {
            const etat = amiActuels[amiId].minimise;
            const discussion = boiteMessage.querySelector('.discussion');
            const input = boiteMessage.querySelector('.input-container');
            
            discussion.style.display = etat ? 'block' : 'none';
            input.style.display = etat ? 'flex' : 'none';
            this.textContent = etat ? '_' : '□';
            amiActuels[amiId].minimise = !etat;
        });

        const inputMessage = boiteMessage.querySelector('.message-text');
        const boutonEnvoyer = boiteMessage.querySelector('.btn-envoyer-message');
        
        function handleEnvoiMessage() {
            const texte = inputMessage.value.trim();
            if (texte) {
                envoyerMessage(amiId, inputMessage, boiteMessage.querySelector('.discussion'));
            }
        }
        
        inputMessage.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                handleEnvoiMessage();
            }
        });
        
        boutonEnvoyer.addEventListener('click', handleEnvoiMessage);
    }
    
    function fermerDiscussionAvecAmi(amiId) {
        if (amiActuels[amiId]) {
            clearInterval(amiActuels[amiId].intervalId);
            amiActuels[amiId].boite.remove();
            delete amiActuels[amiId];
            reorganiserBoites();
        }
    }
    
    function reorganiserBoites() {
        const conteneur = document.getElementById("conteneur-boite-messages");
        const boites = Array.from(conteneur.children);
        const espacement = 10;
        const largeurBoite = 280;
        
        boites.sort((a, b) => {
            const idA = Object.entries(amiActuels).find(([id, obj]) => obj.boite === a)[0];
            const idB = Object.entries(amiActuels).find(([id, obj]) => obj.boite === b)[0];
            return amiActuels[idB].dateOuverture - amiActuels[idA].dateOuverture;
        });

        boites.forEach((boite, index) => {
            if (index < maxBoitesAffichees) {
                boite.style.display = 'block';
                boite.style.right = `${index * (largeurBoite + espacement)}px`;
            } else {
                boite.style.display = 'none';
            }
        });
    }

    function chargerMessages(amiId, messagesContainer) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `charger_messages.php?ami_id=${amiId}`, true);
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const messages = JSON.parse(xhr.responseText);
                    messagesContainer.innerHTML = '';
                    
                    if (messages.length > 0) {
                        messages.forEach(message => {
                            ajouterMessageAuContainer(message, messagesContainer);
                            if (!amiActuels[amiId].dernierMessageId || message.id > amiActuels[amiId].dernierMessageId) {
                                amiActuels[amiId].dernierMessageId = parseInt(message.id);
                            }
                        });
                    } else {
                        const emptyMessage = document.createElement('div');
                        emptyMessage.className = 'no-messages';
                        emptyMessage.textContent = 'Aucun message. Commencez la conversation!';
                        emptyMessage.style.cssText = 'text-align: center; color: #666; padding: 20px;';
                        messagesContainer.appendChild(emptyMessage);
                    }
                    
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                } catch (e) {
                    console.error("Erreur lors du traitement des messages:", e);
                    const errorMessage = document.createElement('div');
                    errorMessage.textContent = "Erreur lors du chargement des messages.";
                    errorMessage.style.cssText = 'text-align: center; color: #f44336; padding: 20px;';
                    messagesContainer.appendChild(errorMessage);
                }
            }
        };
        
        xhr.onerror = function() {
            console.error("Erreur réseau lors de la requête");
            messagesContainer.innerHTML = '<div style="text-align: center; color: #f44336; padding: 20px;">Erreur de connexion</div>';
        };
        
        xhr.send();
    }
    
    function ajouterMessageAuContainer(message, messagesContainer) {
        if (messagesContainer.querySelector(`[data-message-id="${message.id}"]`)) return;
        
        const messageElement = document.createElement('div');
        messageElement.setAttribute('data-message-id', message.id || Date.now());
        
        const messageContent = document.createElement('div');
        messageContent.textContent = message.contenu;
        messageContent.style.cssText = `
            padding: 10px 15px; 
            border-radius: 18px; 
            max-width: 80%; 
            word-wrap: break-word;
            margin: 5px 0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        `;
        
        if (message.est_expediteur) {
            messageElement.style.cssText = 'display: flex; justify-content: flex-end;';
            messageContent.style.backgroundColor = '#4a69bd';
            messageContent.style.color = 'white';
        } else {
            messageElement.style.cssText = 'display: flex; justify-content: flex-start;';
            messageContent.style.backgroundColor = '#e0e0e0';
            messageContent.style.color = 'black';
        }
        
        messageElement.appendChild(messageContent);
        
        const noMessagesElement = messagesContainer.querySelector('.no-messages');
        if (noMessagesElement) noMessagesElement.remove();
        
        messagesContainer.appendChild(messageElement);
        setTimeout(() => messagesContainer.scrollTop = messagesContainer.scrollHeight, 10);
    }
    
    function envoyerMessage(amiId, messageInput, messagesContainer) {
        const texteMessage = messageInput.value.trim();
        if (!texteMessage) return;
        
        const messageTempId = 'temp-' + Date.now();
        const messageTemporaire = {
            id: messageTempId,
            contenu: texteMessage,
            est_expediteur: true,
            date: new Date().toISOString()
        };
        
        ajouterMessageAuContainer(messageTemporaire, messagesContainer);
        messageInput.value = '';
    
        const formData = new FormData();
        formData.append('ami_id', amiId);
        formData.append('message', texteMessage);
    
        fetch('envoyer_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const tempMsg = messagesContainer.querySelector(`[data-message-id="${messageTempId}"]`);
                if (tempMsg) tempMsg.remove();
                
                const messageConfirme = {
                    id: data.message_id,
                    contenu: texteMessage,
                    est_expediteur: true,
                    date: data.date || new Date().toISOString()
                };
                ajouterMessageAuContainer(messageConfirme, messagesContainer);
                
                if (data.message_id) {
                    amiActuels[amiId].dernierMessageId = parseInt(data.message_id);
                }
            } else {
                throw new Error(data.message || "Erreur lors de l'envoi");
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
            const tempMsg = messagesContainer.querySelector(`[data-message-id="${messageTempId}"]`);
            if (tempMsg) {
                const content = tempMsg.querySelector('div');
                content.style.backgroundColor = '#ffdddd';
                content.style.color = '#d32f2f';
                content.textContent = 'Échec: ' + messageTemporaire.contenu;
            }
        });
    }
   
        
    
    
    function mettreAJourMessages(amiId, messagesContainer) {
        if (!amiActuels[amiId]) return;
    
        const dernierMessageId = amiActuels[amiId].dernierMessageId || 0;
        
        fetch(`nouveaux_messages.php?ami_id=${amiId}&dernier_id=${dernierMessageId}&t=${Date.now()}`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur serveur');
            return response.json();
        })
        .then(messages => {
            if (messages?.length > 0) {
                messages.forEach(message => {
                    if (!messagesContainer.querySelector(`[data-message-id="${message.id}"]`)) {
                        ajouterMessageAuContainer(message, messagesContainer);
                        
                        if (!message.est_expediteur && message.id > amiActuels[amiId].dernierMessageId) {
                            amiActuels[amiId].dernierMessageId = parseInt(message.id);
                        }
                    }
                });
            }
        })
        .catch(error => console.error("Erreur mise à jour:", error));
    }
    

    if (boiteAmis) {
        chargerAmis();
    }

});