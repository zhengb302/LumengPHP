#!/bin/bash
# push当前分支到远程

# 获取当前分支名称
branchName=$(git branch | awk '/^\*/ {print $2;}')

git push origin $branchName
