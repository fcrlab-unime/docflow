function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('address') and span.classes:includes('zipcode') then
  span.content = pandoc.Str('xxxxx')
  local value, position = span.classes:find('zipcode')
  span.classes:remove(position)
  local value, position = span.classes:find('address')
  span.classes:remove(position)
end
return span
end