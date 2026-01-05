# ===================================
# CTF-4 : Linux SUID & PATH Hijacking
# ===================================

FROM python:3.11-slim

RUN apt-get update && apt-get install -y \
    gcc \
    sudo \
    vim \
    nano \
    file \
    && rm -rf /var/lib/apt/lists/*


RUN groupadd ctfgroup
RUN useradd -m -s /bin/bash -G ctfgroup ctfuser && \
    echo 'ctfuser:ctf2024' | chpasswd

RUN echo 'CTF{gezg486ZAF}' > /root/flag.txt && \
    chmod 600 /root/flag.txt && \
    chown root:root /root/flag.txt

COPY app/check_system.c /tmp/check_system.c
COPY app/setup_suid.sh /tmp/setup_suid.sh
RUN chmod +x /tmp/setup_suid.sh && \
    /tmp/setup_suid.sh && \
    rm /tmp/setup_suid.sh

RUN echo '#!/bin/bash' > /home/ctfuser/.welcome.sh && \
    echo 'clear' >> /home/ctfuser/.welcome.sh && \
    echo 'cat << "EOF"' >> /home/ctfuser/.welcome.sh && \
    echo '╔═══════════════════════════════════════════════════════╗' >> /home/ctfuser/.welcome.sh && \
    echo '║  CTF-4 : Linux SUID & PATH Hijacking Challenge       ║' >> /home/ctfuser/.welcome.sh && \
    echo '║  Level : Intermediate                                ║' >> /home/ctfuser/.welcome.sh && \
    echo '╚═══════════════════════════════════════════════════════╝' >> /home/ctfuser/.welcome.sh && \
    echo '' >> /home/ctfuser/.welcome.sh && \
    echo ' OBJECTIVE :' >> /home/ctfuser/.welcome.sh && \
    echo '   Read the flag located at /root/flag.txt' >> /home/ctfuser/.welcome.sh && \
    echo '' >> /home/ctfuser/.welcome.sh && \
    echo ' HINTS :' >> /home/ctfuser/.welcome.sh && \
    echo '   - You are ctfuser, a member of the ctfgroup' >> /home/ctfuser/.welcome.sh && \
    echo '   - Look for binaries with special permissions' >> /home/ctfuser/.welcome.sh && \
    echo '   - The "find" command is your friend for enumeration' >> /home/ctfuser/.welcome.sh && \
    echo '   - Think about the PATH variable and its importance' >> /home/ctfuser/.welcome.sh && \
    echo '' >> /home/ctfuser/.welcome.sh && \
    echo ' USEFUL COMMANDS :' >> /home/ctfuser/.welcome.sh && \
    echo '   find / -perm -4000 -type f 2>/dev/null    # Find SUID binaries' >> /home/ctfuser/.welcome.sh && \
    echo '   ls -la /usr/local/bin/                     # Check local binaries' >> /home/ctfuser/.welcome.sh && \
    echo '   file <binary>                              # Examine a binary' >> /home/ctfuser/.welcome.sh && \
    echo '   strings <binary>                           # Read strings inside a binary' >> /home/ctfuser/.welcome.sh && \
    echo '   echo $PATH                                 # Display the current PATH' >> /home/ctfuser/.welcome.sh && \
    echo '' >> /home/ctfuser/.welcome.sh && \
    echo 'Good luck!' >> /home/ctfuser/.welcome.sh && \
    echo 'EOF' >> /home/ctfuser/.welcome.sh && \
    chmod +x /home/ctfuser/.welcome.sh && \
    chown ctfuser:ctfuser /home/ctfuser/.welcome.sh

RUN echo '' >> /home/ctfuser/.bashrc && \
    echo '# Display the welcome message' >> /home/ctfuser/.bashrc && \
    echo 'if [ -f ~/.welcome.sh ]; then' >> /home/ctfuser/.bashrc && \
    echo '    ~/.welcome.sh' >> /home/ctfuser/.bashrc && \
    echo 'fi' >> /home/ctfuser/.bashrc

RUN mkdir -p /opt/ctf && \
    echo '# CTF-4 Hints' > /opt/ctf/hints.txt && \
    echo '' >> /opt/ctf/hints.txt && \
    echo 'Hint 1: SUID binaries run with the privileges of the file owner' >> /opt/ctf/hints.txt && \
    echo 'Hint 2: When a program calls system(), it uses the PATH variable' >> /opt/ctf/hints.txt && \
    echo 'Hint 3: You can modify your PATH to include your own directories' >> /opt/ctf/hints.txt && \
    echo 'Hint 4: Try creating a malicious version of a common command' >> /opt/ctf/hints.txt && \
    echo 'Hint 5: The SUID binary might be in /usr/local/bin/' >> /opt/ctf/hints.txt && \
    chmod 644 /opt/ctf/hints.txt


COPY app/enum.py /home/ctfuser/enum.py
RUN chown ctfuser:ctfuser /home/ctfuser/enum.py && \
    chmod 755 /home/ctfuser/enum.py

WORKDIR /home/ctfuser
USER ctfuser
CMD ["/bin/bash"]
