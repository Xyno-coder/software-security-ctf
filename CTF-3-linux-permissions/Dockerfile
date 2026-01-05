FROM python:3.11-slim

RUN apt-get update && apt-get install -y \
    sudo \
    vim \
    nano \
    && rm -rf /var/lib/apt/lists/*

# Création de l'utilisateur admin avec un mot de passe
RUN useradd -m -s /bin/bash admin && \
    echo 'admin:SecureP@ss2026' | chpasswd

# Création de l'utilisateur player (non privilégié) 
# C'est l'utilisateur par défaut qui démarre dans le conteneur
RUN useradd -m -s /bin/bash player && \
    echo 'player:player123' | chpasswd

# Création du fichier secret contenant le drapeau
RUN echo 'CTF{affe46haf}' > /home/admin/.secret.txt && \
    chown admin:admin /home/admin/.secret.txt && \
    chmod 600 /home/admin/.secret.txt

# Création du fichier de bienvenue dans le répertoire personnel du joueur
RUN echo '=====================================' > /home/player/welcome.txt && \
    echo 'Welcome to the Linux Permissions CTF!' >> /home/player/welcome.txt && \
    echo '=====================================' >> /home/player/welcome.txt && \
    echo '' >> /home/player/welcome.txt && \
    echo 'Your mission: find the hidden flag somewhere in the system.' >> /home/player/welcome.txt && \
    echo '' >> /home/player/welcome.txt && \
    echo 'Hints:' >> /home/player/welcome.txt && \
    echo '- Hidden files start with a dot (.)' >> /home/player/welcome.txt && \
    echo '- Explore the system directories' >> /home/player/welcome.txt && \
    echo '- Check file permissions' >> /home/player/welcome.txt && \
    echo '- There may be other users...' >> /home/player/welcome.txt && \
    echo '' >> /home/player/welcome.txt && \
    echo 'Useful commands: ls -la, cat, cd, sudo, su' >> /home/player/welcome.txt && \
    echo '' >> /home/player/welcome.txt && \
    chown player:player /home/player/welcome.txt

# Création d'un fichier notes lisible contenant le mot de passe admin
RUN mkdir -p /opt/system && \
    echo '# System Configuration Notes' > /opt/system/notes.txt && \
    echo '' >> /opt/system/notes.txt && \
    echo 'Date: 2026-01-01' >> /opt/system/notes.txt && \
    echo 'Admin: admin' >> /opt/system/notes.txt && \
    echo '' >> /opt/system/notes.txt && \
    echo '## Password Management' >> /opt/system/notes.txt && \
    echo 'TODO: Change default admin password!' >> /opt/system/notes.txt && \
    echo 'Current temporary password: SecureP@ss2026' >> /opt/system/notes.txt && \
    echo '' >> /opt/system/notes.txt && \
    echo 'REMINDER: This file should be removed in production!' >> /opt/system/notes.txt && \
    chmod 644 /opt/system/notes.txt

COPY app/helper.py /home/player/helper.py
RUN chown player:player /home/player/helper.py && \
    chmod 755 /home/player/helper.py

WORKDIR /home/player
USER player
RUN echo 'cat /home/player/welcome.txt' >> /home/player/.bashrc
CMD ["/bin/bash"]
