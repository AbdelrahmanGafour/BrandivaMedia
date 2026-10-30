#!/usr/bin/env python3
"""Concatenate fonts.css + src/*.css into a minified assets/css/site.css (comment + whitespace strip only)."""
import re, glob, os
d = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "assets", "css")
src = open(f"{d}/fonts.css").read() + "".join(open(f).read() for f in sorted(glob.glob(f"{d}/src/0*.css")))
src = re.sub(r"/\*.*?\*/", "", src, flags=re.S)
src = re.sub(r"\s+", " ", src)
src = re.sub(r"\s*([{};,>])\s*", r"\1", src)
src = src.replace(";}", "}")
open(f"{d}/site.css", "w").write(src.strip())
print(len(src), "bytes")
