#!/usr/bin/env python3
import sys

def display_banner():

    banner = """
    ╔═══════════════════════════════════════╗
    ║   CTF Linux Permissions -             ║
    ║   Linux System Exploration            ║
    ╚═══════════════════════════════════════╝
    """
    print(banner)

def suggest_commands():
    print("\n Useful Linux commands:")
    commands = [
        ("ls -la", "List all files (including hidden ones)"),
        ("cd /home", "Go to the users directory"),
        ("find / -name '*.txt' 2>/dev/null", "Search for all .txt files"),
        ("grep -r 'password' /opt 2>/dev/null", "Search for the word 'password'"),
        ("cat <file>", "Display the contents of a file"),
        ("su <username>", "Switch user"),
    ]
    
    for cmd, desc in commands:
        print(f"  • {cmd:40} # {desc}")


def main():
    display_banner()

    print("\nYour objective is to find the flag hidden somewhere in the Linux system.")
    print("Hint: the flag is in a hidden file...")
    suggest_commands()
    
    print("\n" + "="*60)
    print("Good luck!")
    print("="*60 + "\n")

if __name__ == "__main__":
    try:
        main()
    except KeyboardInterrupt:
        print("\n\nSee you soon!")
        sys.exit(0)
    except Exception as e:
        print(f"\n Error: {e}", file=sys.stderr)
        sys.exit(1)
