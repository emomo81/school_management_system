#!/bin/bash
# Test MongoDB Atlas connectivity

echo "🔍 Testing MongoDB Atlas DNS..."
nslookup cluster0.hhx1puk.mongodb.net

echo ""
echo "🔍 Testing DNS for MongoDB SRV records..."
nslookup _mongodb._tcp.cluster0.hhx1puk.mongodb.net

echo ""
echo "🔍 Testing basic internet connectivity..."
ping -c 1 8.8.8.8

echo ""
echo "✅ Connectivity tests complete"
