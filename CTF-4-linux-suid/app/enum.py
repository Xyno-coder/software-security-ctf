#!/usr/bin/env python3
"""
CTF-4 Enumeration Helper
"""

import os
import sys


def print_banner():
    banner = """
    ╔═══════════════════════════════════════════╗
    ║   CTF-4 System Enumeration Tool          ║
    ║   Find SUID binaries and vulnerabilities  ║
    ╚═══════════════════════════════════════════╝
    """
    print(banner)



def check_writable_directories():
    print("\n Checking PATH directories for write permissions:")
    
    path_env = os.environ.get('PATH', '')
    path_dirs = path_env.split(':')
    
    writable = []
    for directory in path_dirs:
        if os.path.exists(directory) and os.access(directory, os.W_OK):
            writable.append(directory)
            print(f"  ✓ {directory} (WRITABLE)")
        else:
            print(f"  ✗ {directory}")
    
    if writable:
        print("\n    You can write to some PATH directories!")
        print("      This could be useful for PATH hijacking...")

def show_hints():
    """Affiche des indices pour le challenge."""
    print("\n Enumeration Tips:")
    print("  1. Look for SUID binaries owned by root")
    print("  2. Check what commands they execute")
    print("  3. Try running: strings <binary>")
    print("  4. Think about PATH manipulation")
    print("  5. Can you create a malicious script?")

def main():
    """Fonction principale."""
    print_banner()
    check_writable_directories()
    show_hints()
    
    print("\n" + "=" * 60)
    print("Good luck with the challenge! ")
    print("=" * 60 + "\n")

if __name__ == "__main__":
    try:
        main()
    except KeyboardInterrupt:
        print("\n\n Goodbye!")
        sys.exit(0)
    except Exception as e:
        print(f"\nError: {e}", file=sys.stderr)
        sys.exit(1)
